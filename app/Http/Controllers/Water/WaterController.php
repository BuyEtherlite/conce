<?php

namespace App\Http\Controllers\Water;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WaterController extends Controller
{
    public function index()
    {
        return view('water.index');
    }

    // Connections
    public function connections()
    {
        return view('water.connections.index');
    }

    public function createConnection()
    {
        return view('water.connections.create');
    }

    public function storeConnection(Request $request)
    {
        return redirect()->route('water.connections.index')->with('success', 'Connection created successfully.');
    }

    public function applications()
    {
        return view('water.connections.applications');
    }

    // Meters
    public function meters()
    {
        return view('water.meters.index');
    }

    public function createMeterReading()
    {
        return view('water.meters.create-reading');
    }

    public function storeMeterReading(Request $request)
    {
        return redirect()->route('water.meters.index')->with('success', 'Meter reading recorded successfully.');
    }

    // Billing
    public function billing()
    {
        return view('water.billing.index');
    }

    public function createBill()
    {
        return view('water.billing.create');
    }

    public function storeBill(Request $request)
    {
        return redirect()->route('water.billing.index')->with('success', 'Bill created successfully.');
    }

    public function generateBills()
    {
        return redirect()->route('water.billing.index')->with('success', 'Bills generated successfully.');
    }

    // Quality
    public function qualityIndex()
    {
        return view('water.quality.index');
    }

    public function testsIndex()
    {
        return view('water.quality.tests');
    }

    public function createTest()
    {
        return view('water.quality.create-test');
    }

    public function storeTest(Request $request)
    {
        return redirect()->route('water.quality.tests')->with('success', 'Water quality test recorded successfully.');
    }

    // Rates
    public function ratesIndex()
    {
        return view('water.rates.index');
    }

    public function createRate()
    {
        return view('water.rates.create');
    }

    public function storeRate(Request $request)
    {
        return redirect()->route('water.rates.index')->with('success', 'Water rate created successfully.');
    }

    public function editRate($rate)
    {
        return view('water.rates.edit', compact('rate'));
    }

    public function updateRate(Request $request, $rate)
    {
        return redirect()->route('water.rates.index')->with('success', 'Water rate updated successfully.');
    }

    public function destroyRate($rate)
    {
        return redirect()->route('water.rates.index')->with('success', 'Water rate deleted successfully.');
    }

    // Infrastructure
    public function infrastructure()
    {
        return view('water.infrastructure.index');
    }

    // Reports
    public function reports()
    {
        return view('water.reports.index');
    }
}
