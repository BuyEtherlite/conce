<?php

namespace App\Modules\Utilities\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Utilities\Models\ElectricityConnection;
use App\Modules\Utilities\Models\ElectricityMeter;
use App\Modules\Utilities\Models\ElectricityMeterReading;
use App\Modules\Utilities\Models\GasConnection;
use App\Modules\Utilities\Models\GasMeter;
use App\Modules\Utilities\Models\WasteCollectionCustomer;
use App\Modules\Utilities\Models\WasteCollectionRoute;
use App\Modules\Utilities\Models\FleetVehicle;
use App\Modules\Utilities\Models\InfrastructureAsset;

class UtilitiesController extends Controller
{
    public function index()
    {
        $stats = [
            'electricity_connections' => ElectricityConnection::active()->count(),
            'water_connections' => 2890, // Using existing water management
            'gas_connections' => GasConnection::active()->count(),
            'waste_customers' => WasteCollectionCustomer::active()->count(),
            'fleet_vehicles' => FleetVehicle::active()->count(),
            'maintenance_requests' => InfrastructureAsset::whereHas('maintenanceRequests', function($q) {
                $q->where('status', 'pending');
            })->count(),
            'active_connections' => ElectricityConnection::active()->count() + GasConnection::active()->count() + 2890,
            'service_requests' => 89,
            'monthly_revenue' => 2400000,
            'outages' => 3,
        ];

        return view('utilities.index', compact('stats'));
    }

    // Electricity Management
    public function electricity()
    {
        $stats = [
            'active_connections' => ElectricityConnection::active()->count(),
            'total_consumption' => ElectricityMeterReading::whereHas('meter', function($q) {
                $q->whereHas('connection', function($q2) {
                    $q2->where('status', 'active');
                });
            })->sum('consumption'),
            'outages' => 2,
            'pending_connections' => ElectricityConnection::where('status', 'pending')->count(),
            'residential_connections' => ElectricityConnection::active()->byType('residential')->count(),
            'commercial_connections' => ElectricityConnection::active()->byType('commercial')->count(),
            'industrial_connections' => ElectricityConnection::active()->byType('industrial')->count(),
        ];

        return view('utilities.electricity.index', compact('stats'));
    }

    public function electricityConnections(Request $request)
    {
        $query = ElectricityConnection::with(['activeMeter']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('connection_type', $request->type);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('connection_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('property_address', 'like', '%' . $request->search . '%');
            });
        }

        $connections = $query->latest()->paginate(20);

        return view('utilities.electricity.connections', compact('connections'));
    }

    public function storeElectricityConnection(Request $request)
    {
        $request->validate([
            'connection_number' => 'required|unique:electricity_connections',
            'customer_name' => 'required|string|max:255',
            'property_address' => 'required|string',
            'connection_type' => 'required|in:residential,commercial,industrial',
            'load_capacity' => 'required|numeric|min:0',
            'connection_date' => 'required|date',
            'deposit_amount' => 'nullable|numeric|min:0'
        ]);

        ElectricityConnection::create($request->all());

        return redirect()->route('utilities.electricity.connections')
            ->with('success', 'Electricity connection created successfully');
    }

    public function electricityMeters(Request $request)
    {
        $query = ElectricityMeter::with(['connection', 'latestReading']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $meters = $query->latest()->paginate(20);

        return view('utilities.electricity.meters', compact('meters'));
    }

    public function electricityBilling()
    {
        // Integration with existing billing system
        return view('utilities.electricity.billing');
    }

    public function electricityOutages()
    {
        return view('utilities.electricity.outages');
    }

    // Water Management (using existing water management system)
    public function water()
    {
        $stats = [
            'active_connections' => 2890,
            'daily_consumption' => 2500000,
            'quality_tests' => 45,
            'pending_connections' => 12,
        ];

        return view('utilities.water.index', compact('stats'));
    }

    public function waterConnections()
    {
        return view('utilities.water.connections');
    }

    public function waterMeters()
    {
        return view('utilities.water.meters');
    }

    public function waterBilling()
    {
        return view('utilities.water.billing');
    }

    public function waterQuality()
    {
        return view('utilities.water.quality');
    }

    // Gas Management
    public function gas()
    {
        $stats = [
            'active_connections' => GasConnection::active()->count(),
            'monthly_consumption' => 125000,
            'safety_inspections' => 23,
            'pending_connections' => GasConnection::where('status', 'pending')->count(),
        ];

        return view('utilities.gas.index', compact('stats'));
    }

    public function gasConnections(Request $request)
    {
        $query = GasConnection::with(['activeMeter', 'safetyInspections']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $connections = $query->latest()->paginate(20);

        return view('utilities.gas.connections', compact('connections'));
    }

    public function gasMeters()
    {
        $meters = GasMeter::with(['connection'])->active()->paginate(20);
        return view('utilities.gas.meters', compact('meters'));
    }

    public function gasBilling()
    {
        return view('utilities.gas.billing');
    }

    public function gasSafety()
    {
        return view('utilities.gas.safety');
    }

    // Waste Management
    public function waste()
    {
        $stats = [
            'customers' => WasteCollectionCustomer::active()->count(),
            'collection_routes' => WasteCollectionRoute::active()->count(),
            'recycling_rate' => 65,
            'pending_requests' => 18,
        ];

        return view('utilities.waste.index', compact('stats'));
    }

    public function wasteCollection(Request $request)
    {
        $customers = WasteCollectionCustomer::with('route')->active()->paginate(20);
        return view('utilities.waste.collection', compact('customers'));
    }

    public function wasteRecycling()
    {
        return view('utilities.waste.recycling');
    }

    public function wasteBilling()
    {
        return view('utilities.waste.billing');
    }

    public function wasteRoutes()
    {
        $routes = WasteCollectionRoute::with('customers')->active()->paginate(20);
        return view('utilities.waste.routes', compact('routes'));
    }

    // Infrastructure Management
    public function infrastructure()
    {
        $stats = [
            'roads_total' => InfrastructureAsset::where('asset_type', 'road')->count(),
            'roads_good_condition' => InfrastructureAsset::where('asset_type', 'road')->where('condition', 'good')->count(),
            'streetlights' => InfrastructureAsset::where('asset_type', 'streetlight')->count(),
            'parks' => InfrastructureAsset::where('asset_type', 'park')->count(),
            'maintenance_requests' => 23,
        ];

        return view('utilities.infrastructure.index', compact('stats'));
    }

    public function roads()
    {
        $roads = InfrastructureAsset::where('asset_type', 'road')->paginate(20);
        return view('utilities.infrastructure.roads', compact('roads'));
    }

    public function lighting()
    {
        $lights = InfrastructureAsset::where('asset_type', 'streetlight')->paginate(20);
        return view('utilities.infrastructure.lighting', compact('lights'));
    }

    public function parks()
    {
        $parks = InfrastructureAsset::where('asset_type', 'park')->paginate(20);
        return view('utilities.infrastructure.parks', compact('parks'));
    }

    public function maintenance()
    {
        return view('utilities.infrastructure.maintenance');
    }

    // Fleet Management
    public function fleet()
    {
        $stats = [
            'total_vehicles' => FleetVehicle::count(),
            'active_vehicles' => FleetVehicle::active()->count(),
            'in_maintenance' => FleetVehicle::where('status', 'maintenance')->count(),
            'fuel_consumption' => 2500,
            'monthly_cost' => 85000,
        ];

        return view('utilities.fleet.index', compact('stats'));
    }

    public function fleetVehicles()
    {
        $vehicles = FleetVehicle::with(['maintenanceRecords', 'fuelRecords'])->paginate(20);
        return view('utilities.fleet.vehicles', compact('vehicles'));
    }

    public function fleetMaintenance()
    {
        return view('utilities.fleet.maintenance');
    }

    public function fleetFuel()
    {
        return view('utilities.fleet.fuel');
    }

    public function fleetTracking()
    {
        return view('utilities.fleet.tracking');
    }

    // API endpoints for data
    public function getElectricityData()
    {
        return response()->json([
            'connections' => ElectricityConnection::active()->count(),
            'consumption' => ElectricityMeterReading::sum('consumption'),
            'revenue' => 450000
        ]);
    }

    public function getWasteData()
    {
        return response()->json([
            'customers' => WasteCollectionCustomer::active()->count(),
            'routes' => WasteCollectionRoute::active()->count(),
            'recycling_rate' => 65
        ]);
    }

    public function getFleetData()
    {
        return response()->json([
            'vehicles' => FleetVehicle::active()->count(),
            'maintenance' => FleetVehicle::where('status', 'maintenance')->count(),
            'fuel_cost' => 85000
        ]);
    }
}
