<?php

namespace App\Modules\Water\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Water\Models\WaterConnection;
use App\Modules\Water\Models\WaterMeter;
use App\Modules\Water\Models\WaterMeterReading;
use App\Modules\Water\Models\WaterBill;
use Illuminate\Http\Request;

class WaterController extends Controller
{
    public function index()
    {
        $connections = WaterConnection::with(['meters', 'bills'])
                                     ->latest()
                                     ->paginate(20);

        $summary = [
            'total_connections' => WaterConnection::count(),
            'active_connections' => WaterConnection::where('status', 'active')->count(),
            'inactive_connections' => WaterConnection::where('status', 'inactive')->count(),
            'total_meters' => WaterMeter::count(),
            'active_meters' => WaterMeter::active()->count()
        ];

        return view('water.index', compact('connections', 'summary'));
    }

    public function create()
    {
        $connectionTypes = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'institutional' => 'Institutional'
        ];

        $meterSizes = [
            '15mm' => '15mm',
            '20mm' => '20mm',
            '25mm' => '25mm',
            '32mm' => '32mm',
            '40mm' => '40mm',
            '50mm' => '50mm'
        ];

        return view('water.create', compact('connectionTypes', 'meterSizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'property_address' => 'required|string',
            'connection_type' => 'required|string|in:residential,commercial,industrial,institutional',
            'meter_size' => 'required|string',
            'installation_date' => 'required|date',
            'deposit_amount' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0'
        ]);

        try {
            $connection = new WaterConnection($validated);
            $connection->connection_number = $this->generateConnectionNumber();
            $connection->status = 'pending';
            $connection->created_by = auth()->id();
            $connection->save();

            // Create initial meter if requested
            if ($request->has('install_meter')) {
                $this->createMeter($connection, $request);
            }

            return redirect()->route('water.water.show', $connection)
                           ->with('success', 'Water connection created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create water connection: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $connection = WaterConnection::with(['meters.readings', 'bills'])
                                    ->findOrFail($id);

        $recentReadings = WaterMeterReading::where('connection_id', $id)
                                         ->latest()
                                         ->take(10)
                                         ->get();

        $billingHistory = WaterBill::where('connection_id', $id)
                                  ->latest()
                                  ->take(12)
                                  ->get();

        return view('water.show', compact('connection', 'recentReadings', 'billingHistory'));
    }

    public function edit($id)
    {
        $connection = WaterConnection::findOrFail($id);
        
        $connectionTypes = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'institutional' => 'Institutional'
        ];

        $meterSizes = [
            '15mm' => '15mm',
            '20mm' => '20mm',
            '25mm' => '25mm',
            '32mm' => '32mm',
            '40mm' => '40mm',
            '50mm' => '50mm'
        ];

        return view('water.edit', compact('connection', 'connectionTypes', 'meterSizes'));
    }

    public function update(Request $request, $id)
    {
        $connection = WaterConnection::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'property_address' => 'required|string',
            'connection_type' => 'required|string|in:residential,commercial,industrial,institutional',
            'meter_size' => 'required|string',
            'deposit_amount' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,active,inactive,suspended,disconnected'
        ]);

        try {
            $connection->update($validated);

            return redirect()->route('water.water.show', $connection)
                           ->with('success', 'Water connection updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update water connection: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $connection = WaterConnection::findOrFail($id);
            
            // Check if connection has active bills
            if ($connection->bills()->where('status', 'pending')->exists()) {
                return redirect()->back()
                               ->with('error', 'Cannot delete connection with pending bills');
            }

            $connection->delete();

            return redirect()->route('water.water.index')
                           ->with('success', 'Water connection deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete water connection: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            $connection = WaterConnection::findOrFail($id);
            $connection->update(['status' => 'active']);

            return redirect()->route('water.water.show', $connection)
                           ->with('success', 'Water connection activated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to activate connection: ' . $e->getMessage());
        }
    }

    public function suspend($id)
    {
        try {
            $connection = WaterConnection::findOrFail($id);
            $connection->update(['status' => 'suspended']);

            return redirect()->route('water.water.show', $connection)
                           ->with('success', 'Water connection suspended successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to suspend connection: ' . $e->getMessage());
        }
    }

    public function disconnect($id)
    {
        try {
            $connection = WaterConnection::findOrFail($id);
            $connection->update(['status' => 'disconnected']);

            return redirect()->route('water.water.show', $connection)
                           ->with('success', 'Water connection disconnected successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to disconnect connection: ' . $e->getMessage());
        }
    }

    public function addMeter(Request $request, $id)
    {
        $validated = $request->validate([
            'meter_number' => 'required|string|unique:water_meters,meter_number',
            'meter_type' => 'required|string|in:mechanical,digital,smart',
            'meter_size' => 'required|string',
            'manufacturer' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'installation_date' => 'required|date',
            'location_description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        try {
            $connection = WaterConnection::findOrFail($id);
            
            $meter = new WaterMeter($validated);
            $meter->connection_id = $connection->id;
            $meter->status = 'active';
            $meter->current_reading = 0;
            $meter->last_reading = 0;
            $meter->last_reading_date = now();
            $meter->save();

            return redirect()->route('water.water.show', $connection)
                           ->with('success', 'Water meter added successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to add meter: ' . $e->getMessage());
        }
    }

    public function recordReading(Request $request, $id)
    {
        $validated = $request->validate([
            'meter_id' => 'required|exists:water_meters,id',
            'reading' => 'required|numeric|min:0',
            'reading_date' => 'required|date',
            'reader_name' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $connection = WaterConnection::findOrFail($id);
            $meter = WaterMeter::findOrFail($validated['meter_id']);

            // Validate reading is higher than last reading
            if ($validated['reading'] < $meter->current_reading) {
                return redirect()->back()
                               ->with('error', 'New reading must be higher than current reading');
            }

            // Create reading record
            WaterMeterReading::create([
                'connection_id' => $connection->id,
                'meter_id' => $meter->id,
                'previous_reading' => $meter->current_reading,
                'current_reading' => $validated['reading'],
                'consumption' => $validated['reading'] - $meter->current_reading,
                'reading_date' => $validated['reading_date'],
                'reader_name' => $validated['reader_name'],
                'notes' => $validated['notes'],
                'recorded_by' => auth()->id()
            ]);

            // Update meter
            $meter->update([
                'last_reading' => $meter->current_reading,
                'current_reading' => $validated['reading'],
                'last_reading_date' => $validated['reading_date']
            ]);

            return redirect()->route('water.water.show', $connection)
                           ->with('success', 'Meter reading recorded successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to record reading: ' . $e->getMessage());
        }
    }

    private function generateConnectionNumber()
    {
        $year = date('Y');
        $prefix = 'WC';
        
        $lastConnection = WaterConnection::whereRaw('connection_number LIKE ?', ["{$prefix}{$year}%"])
                                        ->orderBy('connection_number', 'desc')
                                        ->first();

        if ($lastConnection) {
            $lastNumber = intval(substr($lastConnection->connection_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    private function createMeter($connection, $request)
    {
        $meter = new WaterMeter([
            'meter_number' => 'M' . $connection->connection_number,
            'connection_id' => $connection->id,
            'meter_type' => $request->input('meter_type', 'mechanical'),
            'meter_size' => $connection->meter_size,
            'installation_date' => $connection->installation_date,
            'status' => 'active',
            'current_reading' => 0,
            'last_reading' => 0,
            'last_reading_date' => now()
        ]);

        $meter->save();
        return $meter;
    }
}
