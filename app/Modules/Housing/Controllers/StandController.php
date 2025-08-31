<?php

namespace App\Modules\Housing\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Housing\Models\HousingStand;
use App\Modules\Housing\Models\HousingStandArea;
use Illuminate\Http\Request;

class StandController extends Controller
{
    public function index()
    {
        $stands = HousingStand::with(['standArea', 'currentAllocation'])
                              ->paginate(20);

        $summary = [
            'total_stands' => HousingStand::count(),
            'available_stands' => HousingStand::where('status', 'available')->count(),
            'allocated_stands' => HousingStand::where('status', 'allocated')->count(),
            'reserved_stands' => HousingStand::where('status', 'reserved')->count()
        ];

        return view('housing/stand.index', compact('stands', 'summary'));
    }

    public function create()
    {
        $standAreas = HousingStandArea::where('is_active', true)->get();
        
        $statuses = [
            'available' => 'Available',
            'allocated' => 'Allocated',
            'reserved' => 'Reserved',
            'under_development' => 'Under Development'
        ];

        return view('housing/stand.create', compact('standAreas', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stand_area_id' => 'required|exists:housing_stand_areas,id',
            'stand_number' => 'required|string|max:50',
            'size_sqm' => 'required|numeric|min:0',
            'price_per_sqm' => 'required|numeric|min:0',
            'coordinates' => 'nullable|string',
            'facing_direction' => 'nullable|string|max:100',
            'corner_stand' => 'boolean',
            'slope_grade' => 'nullable|string|max:50',
            'utilities_connected' => 'nullable|array',
            'access_road_type' => 'nullable|string|max:100',
            'road_frontage_meters' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:available,allocated,reserved,under_development',
            'special_features' => 'nullable|string',
            'restrictions' => 'nullable|string',
            'is_premium' => 'boolean'
        ]);

        // Calculate total price
        $validated['price_total'] = $validated['size_sqm'] * $validated['price_per_sqm'];
        
        // Generate stand code
        $standArea = HousingStandArea::findOrFail($validated['stand_area_id']);
        $validated['stand_code'] = $standArea->code . '-' . $validated['stand_number'];

        try {
            $stand = HousingStand::create($validated);

            return redirect()->route('housing.stands.show', $stand)
                           ->with('success', 'Stand created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create stand: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $stand = HousingStand::with(['standArea', 'allocations.applicant', 'currentAllocation'])
                             ->findOrFail($id);

        return view('housing/stand.show', compact('stand'));
    }

    public function edit($id)
    {
        $stand = HousingStand::findOrFail($id);
        $standAreas = HousingStandArea::where('is_active', true)->get();
        
        $statuses = [
            'available' => 'Available',
            'allocated' => 'Allocated',
            'reserved' => 'Reserved',
            'under_development' => 'Under Development'
        ];

        return view('housing/stand.edit', compact('stand', 'standAreas', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $stand = HousingStand::findOrFail($id);

        $validated = $request->validate([
            'stand_area_id' => 'required|exists:housing_stand_areas,id',
            'stand_number' => 'required|string|max:50',
            'size_sqm' => 'required|numeric|min:0',
            'price_per_sqm' => 'required|numeric|min:0',
            'coordinates' => 'nullable|string',
            'facing_direction' => 'nullable|string|max:100',
            'corner_stand' => 'boolean',
            'slope_grade' => 'nullable|string|max:50',
            'utilities_connected' => 'nullable|array',
            'access_road_type' => 'nullable|string|max:100',
            'road_frontage_meters' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:available,allocated,reserved,under_development',
            'special_features' => 'nullable|string',
            'restrictions' => 'nullable|string',
            'is_premium' => 'boolean'
        ]);

        // Calculate total price
        $validated['price_total'] = $validated['size_sqm'] * $validated['price_per_sqm'];
        
        // Update stand code if area or number changed
        if ($stand->stand_area_id !== $validated['stand_area_id'] || 
            $stand->stand_number !== $validated['stand_number']) {
            $standArea = HousingStandArea::findOrFail($validated['stand_area_id']);
            $validated['stand_code'] = $standArea->code . '-' . $validated['stand_number'];
        }

        try {
            $stand->update($validated);

            return redirect()->route('housing.stands.show', $stand)
                           ->with('success', 'Stand updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update stand: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $stand = HousingStand::findOrFail($id);
            
            // Check if stand has allocations
            if ($stand->allocations()->exists()) {
                return redirect()->back()
                               ->with('error', 'Cannot delete stand with existing allocations');
            }

            $stand->delete();

            return redirect()->route('housing.stands.index')
                           ->with('success', 'Stand deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete stand: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'stand_ids' => 'required|array',
            'stand_ids.*' => 'exists:housing_stands,id',
            'action' => 'required|string|in:update_status,update_price',
            'status' => 'required_if:action,update_status|string|in:available,allocated,reserved,under_development',
            'price_per_sqm' => 'required_if:action,update_price|numeric|min:0'
        ]);

        try {
            $updateData = [];
            
            if ($validated['action'] === 'update_status') {
                $updateData['status'] = $validated['status'];
            } elseif ($validated['action'] === 'update_price') {
                $updateData['price_per_sqm'] = $validated['price_per_sqm'];
                // Also update total price for each stand
                $stands = HousingStand::whereIn('id', $validated['stand_ids'])->get();
                foreach ($stands as $stand) {
                    $stand->update([
                        'price_per_sqm' => $validated['price_per_sqm'],
                        'price_total' => $stand->size_sqm * $validated['price_per_sqm']
                    ]);
                }
            }

            if (!empty($updateData) && $validated['action'] !== 'update_price') {
                HousingStand::whereIn('id', $validated['stand_ids'])
                           ->update($updateData);
            }

            return redirect()->back()
                           ->with('success', 'Stands updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to update stands: ' . $e->getMessage());
        }
    }
}
