<?php

namespace App\Modules\Markets\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Markets\Models\Market;
use App\Modules\Markets\Models\MarketStall;
use App\Modules\Markets\Models\MarketSection;
use Illuminate\Http\Request;

class StallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stalls = MarketStall::with(['market', 'section', 'currentAllocation'])
            ->paginate(20);

        return view('markets.stalls.index', compact('stalls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Modules\Markets\Models\Market  $market
     * @return \Illuminate\View\View
     */
    public function create(Market $market = null)
    {
        $markets = Market::where('status', 'active')->get();
        $sections = $market ? $market->sections : collect();

        return view('markets.stalls.create', compact('markets', 'sections', 'market'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'market_section_id' => 'required|exists:market_sections,id',
            'stall_number' => 'required|string|max:50',
            'length' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'stall_type' => 'required|in:permanent,temporary,kiosk,booth',
            'monthly_rent' => 'required|numeric|min:0',
            'daily_rent' => 'nullable|numeric|min:0',
            'utilities' => 'nullable|array',
            'features' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        // Calculate area
        $validated['area'] = $validated['length'] * $validated['width'];

        // Generate unique stall code
        $market = Market::find($validated['market_id']);
        $validated['stall_code'] = $market->code . '-' . $validated['stall_number'];

        // Check for duplicate stall number within market
        $exists = MarketStall::where('market_id', $validated['market_id'])
            ->where('stall_number', $validated['stall_number'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['stall_number' => 'Stall number already exists in this market.'])
                ->withInput();
        }

        $stall = MarketStall::create($validated);

        return redirect()->route('markets.stalls.show', $stall)
            ->with('success', 'Stall created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\Markets\Models\MarketStall  $stall
     * @return \Illuminate\View\View
     */
    public function show(MarketStall $stall)
    {
        $stall->load([
            'market',
            'section',
            'currentAllocation.tenant',
            'allocations' => function($query) {
                $query->latest()->take(10);
            },
            'inspections' => function($query) {
                $query->latest()->take(5);
            }
        ]);

        return view('markets.stalls.show', compact('stall'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modules\Markets\Models\MarketStall  $stall
     * @return \Illuminate\View\View
     */
    public function edit(MarketStall $stall)
    {
        $stall->load('market.sections');
        $markets = Market::where('status', 'active')->get();

        return view('markets.stalls.edit', compact('stall', 'markets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Markets\Models\MarketStall  $stall
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MarketStall $stall)
    {
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'market_section_id' => 'required|exists:market_sections,id',
            'stall_number' => 'required|string|max:50',
            'length' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'stall_type' => 'required|in:permanent,temporary,kiosk,booth',
            'monthly_rent' => 'required|numeric|min:0',
            'daily_rent' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'utilities' => 'nullable|array',
            'features' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        // Calculate area
        $validated['area'] = $validated['length'] * $validated['width'];

        // Update stall code if market or stall number changed
        if ($stall->market_id != $validated['market_id'] || $stall->stall_number != $validated['stall_number']) {
            $market = Market::find($validated['market_id']);
            $validated['stall_code'] = $market->code . '-' . $validated['stall_number'];

            // Check for duplicate
            $exists = MarketStall::where('market_id', $validated['market_id'])
                ->where('stall_number', $validated['stall_number'])
                ->where('id', '!=', $stall->id)
                ->exists();

            if ($exists) {
                return back()->withErrors(['stall_number' => 'Stall number already exists in this market.'])
                    ->withInput();
            }
        }

        $stall->update($validated);

        return redirect()->route('markets.stalls.show', $stall)
            ->with('success', 'Stall updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modules\Markets\Models\MarketStall  $stall
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MarketStall $stall)
    {
        if ($stall->currentAllocation) {
            return back()->with('error', 'Cannot delete stall with active allocation.');
        }

        $stall->delete();

        return redirect()->route('markets.stalls.index')
            ->with('success', 'Stall deleted successfully.');
    }

    /**
     * Get sections by market.
     *
     * @param  \App\Modules\Markets\Models\Market  $market
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSectionsByMarket(Market $market)
    {
        return response()->json($market->sections);
    }
}
