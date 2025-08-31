<?php

namespace App\Http\Controllers\Markets;

use App\Http\Controllers\Controller;
use App\Modules\Markets\Models\Market;
use App\Modules\Markets\Models\MarketSection;
use App\Modules\Markets\Models\MarketStall;
use App\Modules\Markets\Models\StallAllocation;
use App\Modules\Markets\Models\MarketRevenueCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::with(['sections', 'stalls'])
            ->withCount(['stalls', 'allocations'])
            ->paginate(10);

        $stats = [
            'total_markets' => Market::count(),
            'total_stalls' => MarketStall::count(),
            'occupied_stalls' => MarketStall::where('status', 'occupied')->count(),
            'total_revenue' => MarketRevenueCollection::where('payment_status', 'paid')
                ->whereMonth('paid_date', now()->month)
                ->sum('amount_paid'),
        ];

        return view('markets.index', compact('markets', 'stats'));
    }

    public function create()
    {
        return view('markets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:markets,code|max:20',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'manager_name' => 'required|string|max:255',
            'manager_phone' => 'required|string|max:20',
            'manager_email' => 'required|email|max:255',
            'total_area' => 'required|numeric|min:0',
            'total_stalls' => 'required|integer|min:1',
            'operating_hours' => 'required|array',
            'operating_days' => 'required|array',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $market = Market::create($validated);

        return redirect()->route('markets.show', $market)
            ->with('success', 'Market created successfully.');
    }

    public function show(Market $market)
    {
        $market->load([
            'sections.stalls',
            'stalls.currentAllocation',
            'allocations' => function($query) {
                $query->where('status', 'active');
            }
        ]);

        $stats = [
            'total_stalls' => $market->stalls->count(),
            'occupied_stalls' => $market->stalls->where('status', 'occupied')->count(),
            'available_stalls' => $market->stalls->where('status', 'available')->count(),
            'monthly_revenue' => $market->getCurrentMonthRevenue(),
            'occupancy_rate' => $market->occupancy_rate,
        ];

        $recentAllocations = StallAllocation::with(['stall', 'tenant'])
            ->whereHas('stall', function($query) use ($market) {
                $query->where('market_id', $market->id);
            })
            ->latest()
            ->take(10)
            ->get();

        $revenueData = MarketRevenueCollection::where('market_id', $market->id)
            ->where('payment_status', 'paid')
            ->selectRaw('DATE(paid_date) as date, SUM(amount_paid) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->take(30)
            ->get();

        return view('markets.show', compact('market', 'stats', 'recentAllocations', 'revenueData'));
    }

    public function edit(Market $market)
    {
        return view('markets.edit', compact('market'));
    }

    public function update(Request $request, Market $market)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:markets,code,' . $market->id,
            'description' => 'nullable|string',
            'address' => 'required|string',
            'manager_name' => 'required|string|max:255',
            'manager_phone' => 'required|string|max:20',
            'manager_email' => 'required|email|max:255',
            'total_area' => 'required|numeric|min:0',
            'total_stalls' => 'required|integer|min:1',
            'operating_hours' => 'required|array',
            'operating_days' => 'required|array',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $market->update($validated);

        return redirect()->route('markets.show', $market)
            ->with('success', 'Market updated successfully.');
    }

    public function destroy(Market $market)
    {
        if ($market->stalls()->where('status', 'occupied')->exists()) {
            return back()->with('error', 'Cannot delete market with occupied stalls.');
        }

        $market->delete();

        return redirect()->route('markets.index')
            ->with('success', 'Market deleted successfully.');
    }

    public function stalls(Market $market)
    {
        $stalls = $market->stalls()
            ->with(['section', 'currentAllocation'])
            ->paginate(20);

        $sections = $market->sections;

        return view('markets.stalls.index', compact('market', 'stalls', 'sections'));
    }

    public function allocations(Market $market)
    {
        $allocations = StallAllocation::with(['stall', 'tenant'])
            ->whereHas('stall', function($query) use ($market) {
                $query->where('market_id', $market->id);
            })
            ->latest()
            ->paginate(20);

        return view('markets.allocations.index', compact('market', 'allocations'));
    }

    // Updated revenue method to handle both specific market and general overview
    public function revenue($marketId = null)
    {
        if ($marketId) {
            $market = Market::findOrFail($marketId);
            $revenueData = MarketRevenueCollection::where('market_id', $marketId)
                ->with('stall')
                ->orderBy('collection_date', 'desc')
                ->paginate(20);

            $totalRevenue = MarketRevenueCollection::where('market_id', $marketId)
                ->sum('amount_collected');

            $monthlyRevenue = MarketRevenueCollection::where('market_id', $marketId)
                ->whereMonth('collection_date', now()->month)
                ->whereYear('collection_date', now()->year)
                ->sum('amount_collected');

            return view('markets.revenue', compact('market', 'revenueData', 'totalRevenue', 'monthlyRevenue'));
        }

        // General revenue overview for all markets
        $totalRevenue = MarketRevenueCollection::sum('amount_collected');
        $monthlyRevenue = MarketRevenueCollection::whereMonth('collection_date', now()->month)
            ->whereYear('collection_date', now()->year)
            ->sum('amount_collected');

        $revenueByMarket = DB::table('market_revenue_collections')
            ->join('markets', 'market_revenue_collections.market_id', '=', 'markets.id')
            ->select('markets.name as market_name', DB::raw('SUM(amount_collected) as total_revenue'))
            ->groupBy('markets.id', 'markets.name')
            ->get();

        return view('markets.revenue.index', compact('totalRevenue', 'monthlyRevenue', 'revenueByMarket'));
    }

    // New method to display vendors
    public function vendors()
    {
        $vendors = DB::table('stall_allocations')
            ->join('market_stalls', 'stall_allocations.stall_id', '=', 'market_stalls.id')
            ->join('markets', 'market_stalls.market_id', '=', 'markets.id')
            ->select(
                'stall_allocations.*',
                'market_stalls.stall_number',
                'markets.name as market_name'
            )
            ->where('stall_allocations.status', 'active')
            ->paginate(20);

        return view('markets.vendors.index', compact('vendors'));
    }

    // New method to create a vendor
    public function createVendor()
    {
        $markets = Market::all();
        $availableStalls = MarketStall::whereNotIn('id', function($query) {
            $query->select('stall_id')
                  ->from('stall_allocations')
                  ->where('status', 'active');
        })->with('market')->get();

        return view('markets.vendors.create', compact('markets', 'availableStalls'));
    }

    // New method to store a vendor
    public function storeVendor(Request $request)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'stall_id' => 'required|exists:market_stalls,id',
            'allocated_date' => 'required|date',
            'rental_amount' => 'required|numeric|min:0',
            'business_type' => 'required|string|max:100'
        ]);

        StallAllocation::create([
            'stall_id' => $request->stall_id,
            'vendor_name' => $request->vendor_name,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'allocated_date' => $request->allocated_date,
            'rental_amount' => $request->rental_amount,
            'business_type' => $request->business_type,
            'status' => 'active'
        ]);

        return redirect()->route('markets.vendors.index')
            ->with('success', 'Vendor allocated successfully.');
    }

    // New method to show a specific vendor
    public function showVendor($id)
    {
        $vendor = StallAllocation::with(['stall.market'])->findOrFail($id);

        return view('markets.vendors.show', compact('vendor'));
    }

    // New method to edit a vendor
    public function editVendor($id)
    {
        $vendor = StallAllocation::findOrFail($id);
        $markets = Market::all();
        $availableStalls = MarketStall::whereNotIn('id', function($query) use ($id) {
            $query->select('stall_id')
                  ->from('stall_allocations')
                  ->where('status', 'active')
                  ->where('id', '!=', $id);
        })->with('market')->get();

        return view('markets.vendors.edit', compact('vendor', 'markets', 'availableStalls'));
    }

    // New method to update a vendor
    public function updateVendor(Request $request, $id)
    {
        $vendor = StallAllocation::findOrFail($id);

        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'rental_amount' => 'required|numeric|min:0',
            'business_type' => 'required|string|max:100',
            'status' => 'required|in:active,inactive,terminated'
        ]);

        $vendor->update($request->only([
            'vendor_name', 'contact_phone', 'contact_email',
            'rental_amount', 'business_type', 'status'
        ]));

        return redirect()->route('markets.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    // New method to delete a vendor
    public function destroyVendor($id)
    {
        $vendor = StallAllocation::findOrFail($id);
        $vendor->delete();

        return redirect()->route('markets.vendors.index')
            ->with('success', 'Vendor allocation removed successfully.');
    }

    // New method for revenue reports
    public function revenueReports()
    {
        $monthlyReport = DB::table('market_revenue_collections')
            ->join('markets', 'market_revenue_collections.market_id', '=', 'markets.id')
            ->select(
                'markets.name as market_name',
                DB::raw('YEAR(collection_date) as year'),
                DB::raw('MONTH(collection_date) as month'),
                DB::raw('SUM(amount_collected) as total_revenue')
            )
            ->groupBy('markets.id', 'markets.name', 'year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('markets.revenue.reports', compact('monthlyReport'));
    }

    // New method to view all revenue collections
    public function revenueCollections()
    {
        $collections = MarketRevenueCollection::with(['market', 'stall'])
            ->orderBy('collection_date', 'desc')
            ->paginate(20);

        return view('markets.revenue.collections', compact('collections'));
    }

    // New method to record a revenue collection
    public function collectRevenue(Request $request)
    {
        $request->validate([
            'stall_id' => 'required|exists:market_stalls,id',
            'amount_collected' => 'required|numeric|min:0',
            'collection_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500'
        ]);

        $stall = MarketStall::findOrFail($request->stall_id);

        MarketRevenueCollection::create([
            'market_id' => $stall->market_id,
            'stall_id' => $request->stall_id,
            'amount_collected' => $request->amount_collected,
            'collection_date' => $request->collection_date,
            'payment_method' => $request->payment_method,
            'collected_by' => auth()->user()->name,
            'notes' => $request->notes
        ]);

        return redirect()->route('markets.revenue.collections')
            ->with('success', 'Revenue collection recorded successfully.');
    }
}