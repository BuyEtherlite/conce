<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Property\Models\Property;
use App\Modules\Property\Models\PropertyCategory;
use App\Modules\Property\Models\PropertyZone;
use App\Modules\Property\Models\PropertyOwner;
use App\Modules\Property\Models\PropertyValuation;
use App\Modules\Property\Models\PropertyLease;
use App\Models\Council;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with(['category', 'zone', 'council', 'primaryOwner', 'currentValuation'])
            ->paginate(15);

        $stats = [
            'total_properties' => Property::count(),
            'active_properties' => Property::where('status', 'active')->count(),
            'residential' => Property::where('property_type', 'residential')->count(),
            'commercial' => Property::where('property_type', 'commercial')->count(),
            'total_value' => Property::sum('market_value'),
            'rented_properties' => Property::whereHas('currentLease')->count()
        ];

        return view('property.index', compact('properties', 'stats'));
    }

    public function create()
    {
        $categories = PropertyCategory::active()->get();
        $zones = PropertyZone::active()->get();
        $councils = Council::where('is_active', true)->get();
        $offices = Office::where('is_active', true)->get();

        return view('property.create', compact('categories', 'zones', 'councils', 'offices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|string|in:residential,commercial,industrial,agricultural,vacant_land,mixed_use',
            'address' => 'required|string|max:500',
            'suburb' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'province' => 'required|string|max:100',
            'size_sqm' => 'nullable|numeric|min:0',
            'size_hectares' => 'nullable|numeric|min:0',
            'erf_number' => 'nullable|string|max:50',
            'title_deed_number' => 'nullable|string|max:100',
            'title_deed_date' => 'nullable|date',
            'surveyor_general_code' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:property_categories,id',
            'zone_id' => 'nullable|exists:property_zones,id',
            'market_value' => 'nullable|numeric|min:0',
            'municipal_value' => 'nullable|numeric|min:0',
            'rental_amount' => 'nullable|numeric|min:0',
            'ownership_type' => 'required|string|in:private,municipal,government,communal,tribal',
            'council_id' => 'required|exists:councils,id',
            'office_id' => 'required|exists:offices,id',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'amenities' => 'nullable|array',
            'utilities' => 'nullable|array',
            'accessibility_features' => 'nullable|array'
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        DB::transaction(function () use ($validated, $request) {
            $property = Property::create($validated);

            // Create owner if provided
            if ($request->filled('owner_type')) {
                $ownerData = $request->validate([
                    'owner_type' => 'required|string|in:individual,company',
                    'first_name' => 'required_if:owner_type,individual|string|max:100',
                    'last_name' => 'required_if:owner_type,individual|string|max:100',
                    'company_name' => 'required_if:owner_type,company|string|max:200',
                    'id_number' => 'nullable|string|max:20',
                    'registration_number' => 'nullable|string|max:50',
                    'email' => 'nullable|email|max:200',
                    'phone' => 'nullable|string|max:20',
                    'address' => 'nullable|string|max:500',
                    'ownership_percentage' => 'nullable|numeric|min:0|max:100'
                ]);

                $ownerData['property_id'] = $property->id;
                $ownerData['ownership_start_date'] = now();
                $ownerData['is_primary'] = true;
                $ownerData['is_active'] = true;
                $ownerData['ownership_percentage'] = $ownerData['ownership_percentage'] ?? 100;

                PropertyOwner::create($ownerData);
            }
        });

        return redirect()->route('property.index')
            ->with('success', 'Property created successfully.');
    }

    public function show(Property $property)
    {
        $property->load([
            'category', 'zone', 'council', 'office', 
            'owners', 'valuations', 'leases', 'inspections', 
            'transfers', 'maintenance', 'documents'
        ]);

        return view('property.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $categories = PropertyCategory::active()->get();
        $zones = PropertyZone::active()->get();
        $councils = Council::where('is_active', true)->get();
        $offices = Office::where('is_active', true)->get();

        return view('property.edit', compact('property', 'categories', 'zones', 'councils', 'offices'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|string|in:residential,commercial,industrial,agricultural,vacant_land,mixed_use',
            'address' => 'required|string|max:500',
            'suburb' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'province' => 'required|string|max:100',
            'size_sqm' => 'nullable|numeric|min:0',
            'size_hectares' => 'nullable|numeric|min:0',
            'erf_number' => 'nullable|string|max:50',
            'title_deed_number' => 'nullable|string|max:100',
            'title_deed_date' => 'nullable|date',
            'surveyor_general_code' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:property_categories,id',
            'zone_id' => 'nullable|exists:property_zones,id',
            'market_value' => 'nullable|numeric|min:0',
            'municipal_value' => 'nullable|numeric|min:0',
            'rental_amount' => 'nullable|numeric|min:0',
            'ownership_type' => 'required|string|in:private,municipal,government,communal,tribal',
            'status' => 'required|string|in:active,inactive,sold,demolished,under_construction,disputed',
            'council_id' => 'required|exists:councils,id',
            'office_id' => 'required|exists:offices,id',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'amenities' => 'nullable|array',
            'utilities' => 'nullable|array',
            'accessibility_features' => 'nullable|array'
        ]);

        $validated['updated_by'] = auth()->id();

        $property->update($validated);

        return redirect()->route('property.show', $property)
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        // Check if property has related records
        if ($property->owners()->exists() || 
            $property->leases()->exists() || 
            $property->transfers()->exists()) {
            return back()->with('error', 'Cannot delete property with related records. Set status to inactive instead.');
        }

        $property->delete();

        return redirect()->route('property.index')
            ->with('success', 'Property deleted successfully.');
    }

    // Property Categories
    public function categories()
    {
        $categories = PropertyCategory::paginate(15);
        return view('property.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('property.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:property_categories,code',
            'description' => 'nullable|string',
            'rate_percentage' => 'nullable|numeric|min:0|max:100',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0'
        ]);

        PropertyCategory::create($validated);

        return redirect()->route('property.categories')
            ->with('success', 'Property category created successfully.');
    }

    // Property Zones
    public function zones()
    {
        $zones = PropertyZone::paginate(15);
        return view('property.zones.index', compact('zones'));
    }

    public function createZone()
    {
        return view('property.zones.create');
    }

    public function storeZone(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:property_zones,code',
            'description' => 'nullable|string',
            'zone_multiplier' => 'nullable|numeric|min:0'
        ]);

        PropertyZone::create($validated);

        return redirect()->route('property.zones')
            ->with('success', 'Property zone created successfully.');
    }

    // Property Valuations
    public function valuations(Property $property)
    {
        $valuations = $property->valuations()->with('creator')->paginate(10);
        return view('property.valuations.index', compact('property', 'valuations'));
    }

    public function createValuation(Property $property)
    {
        return view('property.valuations.create', compact('property'));
    }

    public function storeValuation(Request $request, Property $property)
    {
        $validated = $request->validate([
            'valuation_date' => 'required|date',
            'land_value' => 'required|numeric|min:0',
            'building_value' => 'required|numeric|min:0',
            'total_value' => 'required|numeric|min:0',
            'valuation_method' => 'required|string|in:market,replacement,income,comparative',
            'valuer_name' => 'required|string|max:100',
            'valuer_license' => 'nullable|string|max:50',
            'valuer_company' => 'nullable|string|max:200',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'remarks' => 'nullable|string'
        ]);

        $validated['property_id'] = $property->id;
        $validated['created_by'] = auth()->id();

        PropertyValuation::create($validated);

        return redirect()->route('property.valuations', $property)
            ->with('success', 'Property valuation created successfully.');
    }

    // Property Leases
    public function leases(Property $property)
    {
        $leases = $property->leases()->with('manager')->paginate(10);
        return view('property.leases.index', compact('property', 'leases'));
    }

    public function createLease(Property $property)
    {
        return view('property.leases.create', compact('property'));
    }

    public function storeLease(Request $request, Property $property)
    {
        $validated = $request->validate([
            'tenant_name' => 'required|string|max:200',
            'tenant_id_number' => 'nullable|string|max:20',
            'tenant_email' => 'nullable|email|max:200',
            'tenant_phone' => 'nullable|string|max:20',
            'tenant_address' => 'nullable|string|max:500',
            'lease_start_date' => 'required|date',
            'lease_end_date' => 'required|date|after:lease_start_date',
            'monthly_rental' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'annual_escalation' => 'nullable|numeric|min:0|max:100',
            'payment_frequency' => 'required|string|in:monthly,quarterly,annually',
            'payment_day' => 'required|integer|min:1|max:31',
            'lease_type' => 'required|string|in:residential,commercial,agricultural',
            'special_conditions' => 'nullable|string',
            'notice_period_days' => 'nullable|integer|min:0'
        ]);

        $validated['property_id'] = $property->id;
        $validated['managed_by'] = auth()->id();

        PropertyLease::create($validated);

        return redirect()->route('property.leases', $property)
            ->with('success', 'Property lease created successfully.');
    }

    // Search and Filter
    public function search(Request $request)
    {
        $query = Property::with(['category', 'zone', 'council', 'primaryOwner']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('property_code', 'like', "%{$searchTerm}%")
                  ->orWhere('title', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%")
                  ->orWhere('erf_number', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('council_id')) {
            $query->where('council_id', $request->council_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $properties = $query->paginate(15);
        $categories = PropertyCategory::active()->get();
        $councils = Council::where('is_active', true)->get();

        return view('property.search', compact('properties', 'categories', 'councils'));
    }

    // Reports
    public function reports()
    {
        $propertyTypeStats = Property::selectRaw('property_type, COUNT(*) as count, SUM(market_value) as total_value')
            ->groupBy('property_type')
            ->get();

        $councilStats = Property::with('council')
            ->selectRaw('council_id, COUNT(*) as count, SUM(market_value) as total_value')
            ->groupBy('council_id')
            ->get();

        $monthlyStats = Property::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('property.reports.index', compact('propertyTypeStats', 'councilStats', 'monthlyStats'));
    }
}
<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Property\Models\Property;
use App\Modules\Property\Models\PropertyValuation;
use App\Modules\Property\Models\PropertyLease;
use App\Modules\Property\Models\PropertyOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index()
    {
        $stats = [
            'total_properties' => Property::count(),
            'active_leases' => PropertyLease::where('status', 'active')->count(),
            'total_valuations' => PropertyValuation::count(),
            'total_value' => PropertyValuation::sum('total_value'),
        ];

        $recentProperties = Property::with(['owner', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('property.index', compact('stats', 'recentProperties'));
    }

    public function create()
    {
        return view('property.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_number' => 'required|string|unique:properties,property_number',
            'title_deed_number' => 'nullable|string',
            'address' => 'required|string',
            'property_type' => 'required|string|in:residential,commercial,industrial,vacant_land',
            'size' => 'required|numeric|min:0',
            'size_unit' => 'required|string|in:sqm,hectares,acres',
            'zoning' => 'nullable|string',
            'status' => 'required|string|in:available,occupied,under_development',
        ]);

        Property::create($validated);

        return redirect()->route('property.index')
            ->with('success', 'Property created successfully.');
    }

    public function show(Property $property)
    {
        $property->load(['owner', 'valuations', 'leases']);
        return view('property.show', compact('property'));
    }

    public function edit(Property $property)
    {
        return view('property.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title_deed_number' => 'nullable|string',
            'address' => 'required|string',
            'property_type' => 'required|string|in:residential,commercial,industrial,vacant_land',
            'size' => 'required|numeric|min:0',
            'size_unit' => 'required|string|in:sqm,hectares,acres',
            'zoning' => 'nullable|string',
            'status' => 'required|string|in:available,occupied,under_development',
        ]);

        $property->update($validated);

        return redirect()->route('property.show', $property)
            ->with('success', 'Property updated successfully.');
    }

    public function landRecords()
    {
        $landRecords = Property::with(['owner'])
            ->where('property_type', 'vacant_land')
            ->paginate(15);

        return view('property.land-records', compact('landRecords'));
    }

    public function valuations()
    {
        $valuations = PropertyValuation::with(['property'])
            ->latest()
            ->paginate(15);

        return view('property.valuations', compact('valuations'));
    }

    public function leases()
    {
        $leases = PropertyLease::with(['property', 'tenant'])
            ->latest()
            ->paginate(15);

        return view('property.leases', compact('leases'));
    }
}
