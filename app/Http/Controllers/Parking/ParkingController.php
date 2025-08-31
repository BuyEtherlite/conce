<?php

namespace App\Http\Controllers\Parking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function index()
    {
        return view('parking.index');
    }

    // Zones
    public function zones()
    {
        return view('parking.zones.index');
    }

    public function createZone()
    {
        return view('parking.zones.create');
    }

    public function storeZone(Request $request)
    {
        return redirect()->route('parking.zones.index')->with('success', 'Zone created successfully.');
    }

    public function showZone($zone)
    {
        return view('parking.zones.show', compact('zone'));
    }

    public function editZone($zone)
    {
        return view('parking.zones.edit', compact('zone'));
    }

    public function updateZone(Request $request, $zone)
    {
        return redirect()->route('parking.zones.index')->with('success', 'Zone updated successfully.');
    }

    // Spaces
    public function spaces()
    {
        return view('parking.spaces.index');
    }

    public function createSpace()
    {
        return view('parking.spaces.create');
    }

    public function storeSpace(Request $request)
    {
        return redirect()->route('parking.spaces.index')->with('success', 'Space created successfully.');
    }

    public function showSpace($space)
    {
        return view('parking.spaces.show', compact('space'));
    }

    public function editSpace($space)
    {
        return view('parking.spaces.edit', compact('space'));
    }

    public function updateSpace(Request $request, $space)
    {
        return redirect()->route('parking.spaces.index')->with('success', 'Space updated successfully.');
    }

    // Permits
    public function permits()
    {
        return view('parking.permits.index');
    }

    public function createPermit()
    {
        return view('parking.permits.create');
    }

    public function storePermit(Request $request)
    {
        return redirect()->route('parking.permits.index')->with('success', 'Permit created successfully.');
    }

    public function showPermit($permit)
    {
        return view('parking.permits.show', compact('permit'));
    }

    public function editPermit($permit)
    {
        return view('parking.permits.edit', compact('permit'));
    }

    public function updatePermit(Request $request, $permit)
    {
        return redirect()->route('parking.permits.index')->with('success', 'Permit updated successfully.');
    }

    // Violations
    public function violations()
    {
        return view('parking.violations.index');
    }

    public function createViolation()
    {
        return view('parking.violations.create');
    }

    public function storeViolation(Request $request)
    {
        return redirect()->route('parking.violations.index')->with('success', 'Violation created successfully.');
    }

    public function showViolation($violation)
    {
        return view('parking.violations.show', compact('violation'));
    }

    public function editViolation($violation)
    {
        return view('parking.violations.edit', compact('violation'));
    }

    public function updateViolation(Request $request, $violation)
    {
        return redirect()->route('parking.violations.index')->with('success', 'Violation updated successfully.');
    }

    public function processPayment(Request $request, $violation)
    {
        return redirect()->back()->with('success', 'Payment processed successfully.');
    }

    // Reports
    public function reports()
    {
        return view('parking.reports.index');
    }

    public function revenueReport()
    {
        return view('parking.reports.revenue');
    }

    public function violationsReport()
    {
        return view('parking.reports.violations');
    }
}
