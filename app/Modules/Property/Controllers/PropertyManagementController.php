<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Property\Models\Property;
use Illuminate\Http\Request;

class PropertyManagementController extends Controller
{
    public function index()
    {
        $properties = Property::with(['owner', 'category'])
            ->paginate(15);

        return view('property.management.index', compact('properties'));
    }

    public function landRecords()
    {
        return view('property.land-records');
    }

    public function leases()
    {
        return view('property.management.leases');
    }

    public function valuations()
    {
        return view('property.management.valuations');
    }

    public function transfers()
    {
        return view('property.transfers');
    }

    public function maintenance()
    {
        return view('property.management.maintenance');
    }

    public function inspections()
    {
        return view('property.management.inspections');
    }
}