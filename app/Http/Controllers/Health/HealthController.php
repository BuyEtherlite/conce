<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index()
    {
        return view('health.index');
    }

    public function facilities()
    {
        return view('health.facilities.index');
    }

    public function permits()
    {
        return view('health.permits.index');
    }

    public function createPermit()
    {
        return view('health.permits.create');
    }

    public function storePermit(Request $request)
    {
        // Implementation here
        return redirect()->route('health.permits.index');
    }

    public function inspections()
    {
        return view('health.inspections.index');
    }

    public function createInspection()
    {
        return view('health.inspections.create');
    }

    public function storeInspection(Request $request)
    {
        // Implementation here
        return redirect()->route('health.inspections.index');
    }

    public function practitioners()
    {
        return view('health.practitioners.index');
    }

    public function environmental()
    {
        return view('health.environmental.index');
    }

    public function foodSafety()
    {
        return view('health.food-safety.index');
    }

    public function immunization()
    {
        return view('health.immunization.index');
    }

    public function records()
    {
        return view('health.records.index');
    }

    public function emergency()
    {
        return view('health.emergency.index');
    }

    public function quality()
    {
        return view('health.quality.index');
    }

    public function services()
    {
        return view('health.services.index');
    }
}
