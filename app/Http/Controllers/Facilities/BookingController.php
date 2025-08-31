<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $stats = [
            'total_facilities' => 15,
            'available_facilities' => 12,
            'booked_today' => 8,
            'revenue_today' => 2500,
            'pools_available' => 2,
            'pools_maintenance' => 1,
            'halls_available' => 2,
            'halls_booked' => 1,
            'sports_available' => 5,
            'sports_booked' => 2,
        ];

        return view('facilities.bookings.index', compact('stats'));
    }

    public function create()
    {
        return view('facilities.bookings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_type' => 'required|string',
            'facility_name' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'required|string|max:20',
        ]);

        // Store booking logic would go here
        
        return redirect()->route('facilities.bookings.index')
            ->with('success', 'Facility booking created successfully.');
    }

    public function show($id)
    {
        return view('facilities.bookings.show', compact('id'));
    }

    public function edit($id)
    {
        return view('facilities.bookings.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('facilities.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy($id)
    {
        return redirect()->route('facilities.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    // Pool methods
    public function pools()
    {
        $pools = [
            ['id' => 1, 'name' => 'Olympic Pool', 'status' => 'available', 'capacity' => 50, 'hourly_rate' => 150],
            ['id' => 2, 'name' => 'Children\'s Pool', 'status' => 'available', 'capacity' => 20, 'hourly_rate' => 100],
            ['id' => 3, 'name' => 'Therapy Pool', 'status' => 'maintenance', 'capacity' => 15, 'hourly_rate' => 120],
        ];
        
        return view('facilities.pools.index', compact('pools'));
    }

    public function createPool()
    {
        return view('facilities.pools.create');
    }

    public function storePool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        return redirect()->route('facilities.pools')
            ->with('success', 'Pool created successfully.');
    }

    public function showPool($id)
    {
        return view('facilities.pools.show', compact('id'));
    }

    public function editPool($id)
    {
        return view('facilities.pools.edit', compact('id'));
    }

    public function updatePool(Request $request, $id)
    {
        return redirect()->route('facilities.pools')
            ->with('success', 'Pool updated successfully.');
    }

    public function destroyPool($id)
    {
        return redirect()->route('facilities.pools')
            ->with('success', 'Pool deleted successfully.');
    }

    // Hall methods
    public function halls()
    {
        $halls = [
            ['id' => 1, 'name' => 'Main Hall', 'status' => 'available', 'capacity' => 200, 'hourly_rate' => 300],
            ['id' => 2, 'name' => 'Conference Room A', 'status' => 'booked', 'capacity' => 50, 'hourly_rate' => 150],
            ['id' => 3, 'name' => 'Multi-Purpose Room', 'status' => 'available', 'capacity' => 100, 'hourly_rate' => 200],
        ];
        
        return view('facilities.halls.index', compact('halls'));
    }

    public function createHall()
    {
        return view('facilities.halls.create');
    }

    public function storeHall(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        return redirect()->route('facilities.halls')
            ->with('success', 'Hall created successfully.');
    }

    public function showHall($id)
    {
        return view('facilities.halls.show', compact('id'));
    }

    public function editHall($id)
    {
        return view('facilities.halls.edit', compact('id'));
    }

    public function updateHall(Request $request, $id)
    {
        return redirect()->route('facilities.halls')
            ->with('success', 'Hall updated successfully.');
    }

    public function destroyHall($id)
    {
        return redirect()->route('facilities.halls')
            ->with('success', 'Hall deleted successfully.');
    }

    // Sports facilities methods
    public function sports()
    {
        $sports = [
            ['id' => 1, 'name' => 'Tennis Court 1', 'status' => 'available', 'sport_type' => 'Tennis', 'hourly_rate' => 80],
            ['id' => 2, 'name' => 'Basketball Court', 'status' => 'booked', 'sport_type' => 'Basketball', 'hourly_rate' => 100],
            ['id' => 3, 'name' => 'Football Field', 'status' => 'available', 'sport_type' => 'Football', 'hourly_rate' => 200],
            ['id' => 4, 'name' => 'Volleyball Court', 'status' => 'available', 'sport_type' => 'Volleyball', 'hourly_rate' => 70],
            ['id' => 5, 'name' => 'Badminton Court 1', 'status' => 'booked', 'sport_type' => 'Badminton', 'hourly_rate' => 60],
        ];
        
        return view('facilities.sports.index', compact('sports'));
    }

    public function createSports()
    {
        return view('facilities.sports.create');
    }

    public function storeSports(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sport_type' => 'required|string|max:100',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        return redirect()->route('facilities.sports')
            ->with('success', 'Sports facility created successfully.');
    }

    public function showSports($id)
    {
        return view('facilities.sports.show', compact('id'));
    }

    public function editSports($id)
    {
        return view('facilities.sports.edit', compact('id'));
    }

    public function updateSports(Request $request, $id)
    {
        return redirect()->route('facilities.sports')
            ->with('success', 'Sports facility updated successfully.');
    }

    public function destroySports($id)
    {
        return redirect()->route('facilities.sports')
            ->with('success', 'Sports facility deleted successfully.');
    }

    // Gate Takings methods
    public function gateTakings()
    {
        $gateTakings = [
            ['id' => 1, 'date' => '2025-01-08', 'facility' => 'Olympic Pool', 'amount' => 450, 'visitors' => 15],
            ['id' => 2, 'date' => '2025-01-08', 'facility' => 'Main Hall', 'amount' => 600, 'visitors' => 8],
            ['id' => 3, 'date' => '2025-01-07', 'facility' => 'Tennis Court 1', 'amount' => 240, 'visitors' => 4],
        ];
        
        return view('facilities.gate-takings.index', compact('gateTakings'));
    }

    public function createGateTaking()
    {
        return view('facilities.gate-takings.create');
    }

    public function storeGateTaking(Request $request)
    {
        $request->validate([
            'facility' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'visitors' => 'required|integer|min:0',
        ]);

        return redirect()->route('facilities.gate-takings')
            ->with('success', 'Gate taking recorded successfully.');
    }

    public function showGateTaking($id)
    {
        return view('facilities.gate-takings.show', compact('id'));
    }

    public function editGateTaking($id)
    {
        return view('facilities.gate-takings.edit', compact('id'));
    }

    public function updateGateTaking(Request $request, $id)
    {
        return redirect()->route('facilities.gate-takings')
            ->with('success', 'Gate taking updated successfully.');
    }

    public function destroyGateTaking($id)
    {
        return redirect()->route('facilities.gate-takings')
            ->with('success', 'Gate taking deleted successfully.');
    }

    // Calendar and other views
    public function calendar()
    {
        return view('facilities.calendar.index');
    }

    public function schedule()
    {
        return view('facilities.schedule.index');
    }

    public function maintenance()
    {
        $maintenanceRecords = [
            ['id' => 1, 'facility' => 'Therapy Pool', 'type' => 'Cleaning', 'scheduled_date' => '2025-01-10', 'status' => 'scheduled'],
            ['id' => 2, 'facility' => 'Tennis Court 2', 'type' => 'Resurfacing', 'scheduled_date' => '2025-01-15', 'status' => 'pending'],
        ];
        
        return view('facilities.maintenance.index', compact('maintenanceRecords'));
    }

    public function availability()
    {
        return view('facilities.availability.index');
    }
}
