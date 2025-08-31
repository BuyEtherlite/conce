<?php

namespace App\Http\Controllers\Cemeteries;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cemetery\CemeteryPlot;
use App\Models\Cemetery\BurialRecord;
use App\Models\Cemetery\CemeteryMaintenance;
use App\Models\Cemetery\CemeterySection;
use App\Models\Cemetery\CemeteryReservation;
use Illuminate\Support\Facades\DB;

class CemeteryController extends Controller
{
    public function index()
    {
        $stats = [
            'total_plots' => CemeteryPlot::count(),
            'available_plots' => CemeteryPlot::where('status', 'available')->count(),
            'occupied_plots' => CemeteryPlot::where('status', 'occupied')->count(),
            'reserved_plots' => CemeteryPlot::where('status', 'reserved')->count(),
            'total_burials' => BurialRecord::count(),
            'pending_maintenance' => CemeteryMaintenance::where('status', 'scheduled')->count(),
        ];

        return view('cemeteries.index', compact('stats'));
    }

    // Plot Management Methods
    public function plots()
    {
        $plots = CemeteryPlot::with('burialRecords')->paginate(20);
        $sections = collect(); // Empty collection as table doesn't exist
        return view('cemeteries.plots.index', compact('plots', 'sections'));
    }

    public function createPlot()
    {
        $sections = CemeterySection::all();
        return view('cemeteries.plots.create', compact('sections'));
    }

    public function storePlot(Request $request)
    {
        $request->validate([
            'plot_number' => 'required|string|max:50|unique:cemetery_plots',
            'section' => 'required|string|max:100',
            'size' => 'required|in:Single,Double,Family',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,reserved,occupied',
            'description' => 'nullable|string',
            'gps_latitude' => 'nullable|numeric|between:-90,90',
            'gps_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        CemeteryPlot::create($request->all());

        return redirect()->route('cemeteries.plots.index')->with('success', 'Cemetery plot created successfully');
    }

    public function showPlot($id)
    {
        $plot = CemeteryPlot::with(['burialRecords', 'reservations'])->findOrFail($id);
        return view('cemeteries.plots.show', compact('plot'));
    }

    public function editPlot($id)
    {
        $plot = CemeteryPlot::findOrFail($id);
        $sections = CemeterySection::all();
        return view('cemeteries.plots.edit', compact('plot', 'sections'));
    }

    public function updatePlot(Request $request, $id)
    {
        $request->validate([
            'plot_number' => 'required|string|max:50|unique:cemetery_plots,plot_number,' . $id,
            'section' => 'required|string|max:100',
            'size' => 'required|in:Single,Double,Family',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,reserved,occupied',
            'description' => 'nullable|string',
            'gps_latitude' => 'nullable|numeric|between:-90,90',
            'gps_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $plot = CemeteryPlot::findOrFail($id);
        $plot->update($request->all());

        return redirect()->route('cemeteries.plots.index')->with('success', 'Cemetery plot updated successfully');
    }

    public function destroyPlot($id)
    {
        $plot = CemeteryPlot::findOrFail($id);
        
        if ($plot->burialRecords()->count() > 0) {
            return redirect()->route('cemeteries.plots.index')->with('error', 'Cannot delete plot with existing burial records');
        }

        $plot->delete();
        return redirect()->route('cemeteries.plots.index')->with('success', 'Cemetery plot deleted successfully');
    }

    // Burial Management Methods
    public function burials()
    {
        $burials = BurialRecord::with('plot')->paginate(20);
        return view('cemeteries.burials.index', compact('burials'));
    }

    public function createBurial()
    {
        $plots = CemeteryPlot::where('status', '!=', 'occupied')->get();
        return view('cemeteries.burials.create', compact('plots'));
    }

    public function storeBurial(Request $request)
    {
        $request->validate([
            'plot_id' => 'required|exists:cemetery_plots,id',
            'deceased_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'required|date',
            'burial_date' => 'required|date',
            'age_at_death' => 'nullable|integer|min:0',
            'cause_of_death' => 'nullable|string|max:255',
            'next_of_kin' => 'required|string|max:255',
            'next_of_kin_relationship' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'burial_notes' => 'nullable|string',
            'funeral_director' => 'nullable|string|max:255',
            'burial_fee' => 'nullable|numeric|min:0',
            'payment_status' => 'boolean',
        ]);

        DB::transaction(function () use ($request) {
            BurialRecord::create($request->all());
            
            // Update plot status to occupied
            CemeteryPlot::findOrFail($request->plot_id)->update(['status' => 'occupied']);
        });

        return redirect()->route('cemeteries.burials.index')->with('success', 'Burial record created successfully');
    }

    public function showBurial($id)
    {
        $burial = BurialRecord::with('plot')->findOrFail($id);
        return view('cemeteries.burials.show', compact('burial'));
    }

    public function editBurial($id)
    {
        $burial = BurialRecord::with('plot')->findOrFail($id);
        $plots = CemeteryPlot::where('status', '!=', 'occupied')
                             ->orWhere('id', $burial->plot_id)
                             ->get();
        return view('cemeteries.burials.edit', compact('burial', 'plots'));
    }

    public function updateBurial(Request $request, $id)
    {
        $request->validate([
            'plot_id' => 'required|exists:cemetery_plots,id',
            'deceased_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'required|date',
            'burial_date' => 'required|date',
            'age_at_death' => 'nullable|integer|min:0',
            'cause_of_death' => 'nullable|string|max:255',
            'next_of_kin' => 'required|string|max:255',
            'next_of_kin_relationship' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'burial_notes' => 'nullable|string',
            'funeral_director' => 'nullable|string|max:255',
            'burial_fee' => 'nullable|numeric|min:0',
            'payment_status' => 'boolean',
        ]);

        $burial = BurialRecord::findOrFail($id);
        $oldPlotId = $burial->plot_id;
        
        DB::transaction(function () use ($request, $burial, $oldPlotId) {
            $burial->update($request->all());
            
            // If plot changed, update plot statuses
            if ($oldPlotId != $request->plot_id) {
                // Set old plot as available (if no other burials)
                if (BurialRecord::where('plot_id', $oldPlotId)->count() == 0) {
                    CemeteryPlot::findOrFail($oldPlotId)->update(['status' => 'available']);
                }
                
                // Set new plot as occupied
                CemeteryPlot::findOrFail($request->plot_id)->update(['status' => 'occupied']);
            }
        });

        return redirect()->route('cemeteries.burials.index')->with('success', 'Burial record updated successfully');
    }

    public function destroyBurial($id)
    {
        $burial = BurialRecord::findOrFail($id);
        $plotId = $burial->plot_id;
        
        DB::transaction(function () use ($burial, $plotId) {
            $burial->delete();
            
            // Check if plot should be set back to available
            if (BurialRecord::where('plot_id', $plotId)->count() == 0) {
                CemeteryPlot::findOrFail($plotId)->update(['status' => 'available']);
            }
        });

        return redirect()->route('cemeteries.burials.index')->with('success', 'Burial record deleted successfully');
    }

    // Maintenance Management Methods
    public function maintenance()
    {
        $maintenance = CemeteryMaintenance::orderBy('scheduled_date', 'desc')->paginate(20);
        return view('cemeteries.maintenance.index', compact('maintenance'));
    }

    public function createMaintenance()
    {
        return view('cemeteries.maintenance.create');
    }

    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date',
            'assigned_to' => 'nullable|string|max:255',
            'estimated_cost' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'maintenance_type' => 'required|in:cleaning,repair,landscaping,security,other',
        ]);

        CemeteryMaintenance::create($request->all());

        return redirect()->route('cemeteries.maintenance.index')->with('success', 'Maintenance task created successfully');
    }

    public function showMaintenance($id)
    {
        $maintenance = CemeteryMaintenance::findOrFail($id);
        return view('cemeteries.maintenance.show', compact('maintenance'));
    }

    public function editMaintenance($id)
    {
        $maintenance = CemeteryMaintenance::findOrFail($id);
        return view('cemeteries.maintenance.edit', compact('maintenance'));
    }

    public function updateMaintenance(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date',
            'completed_date' => 'nullable|date',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'assigned_to' => 'nullable|string|max:255',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'maintenance_notes' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'maintenance_type' => 'required|in:cleaning,repair,landscaping,security,other',
        ]);

        $maintenance = CemeteryMaintenance::findOrFail($id);
        $maintenance->update($request->all());

        return redirect()->route('cemeteries.maintenance.index')->with('success', 'Maintenance task updated successfully');
    }

    public function destroyMaintenance($id)
    {
        $maintenance = CemeteryMaintenance::findOrFail($id);
        $maintenance->delete();
        
        return redirect()->route('cemeteries.maintenance.index')->with('success', 'Maintenance task deleted successfully');
    }

    // Section Management Methods
    public function sections()
    {
        $sections = CemeterySection::withCount('plots')->paginate(20);
        return view('cemeteries.sections.index', compact('sections'));
    }

    public function createSection()
    {
        return view('cemeteries.sections.create');
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:255|unique:cemetery_sections',
            'description' => 'nullable|string',
            'total_plots' => 'required|integer|min:0',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        CemeterySection::create($request->all());

        return redirect()->route('cemeteries.sections.index')->with('success', 'Cemetery section created successfully');
    }

    // Reservation Management Methods
    public function reservations()
    {
        $reservations = CemeteryReservation::with('plot')->paginate(20);
        return view('cemeteries.reservations.index', compact('reservations'));
    }

    public function createReservation()
    {
        $plots = CemeteryPlot::where('status', 'available')->get();
        return view('cemeteries.reservations.create', compact('plots'));
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'plot_id' => 'required|exists:cemetery_plots,id',
            'reserved_by_name' => 'required|string|max:255',
            'reserved_by_phone' => 'nullable|string|max:20',
            'reserved_by_email' => 'nullable|email|max:255',
            'reservation_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:reservation_date',
            'reservation_fee' => 'nullable|numeric|min:0',
            'payment_status' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            CemeteryReservation::create($request->all());
            
            // Update plot status to reserved
            CemeteryPlot::findOrFail($request->plot_id)->update(['status' => 'reserved']);
        });

        return redirect()->route('cemeteries.reservations.index')->with('success', 'Plot reservation created successfully');
    }

    // Reports
    public function reports()
    {
        $data = [
            'burial_stats' => BurialRecord::selectRaw('YEAR(burial_date) as year, COUNT(*) as count')
                              ->groupBy('year')
                              ->orderBy('year', 'desc')
                              ->limit(5)
                              ->get(),
            'plot_utilization' => CemeteryPlot::selectRaw('status, COUNT(*) as count')
                                   ->groupBy('status')
                                   ->get(),
            'maintenance_summary' => CemeteryMaintenance::selectRaw('status, COUNT(*) as count')
                                     ->groupBy('status')
                                     ->get(),
        ];

        return view('cemeteries.reports.index', compact('data'));
    }
}
