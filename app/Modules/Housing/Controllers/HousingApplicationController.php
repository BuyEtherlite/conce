<?php

namespace App\Modules\Housing\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HousingApplicationController extends Controller
{
    public function index()
    {
        return view('housing/housingapplication.index');
    }

    public function create()
    {
        return view('housing/housingapplication.create');
    }

    public function store(Request $request)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function show($id)
    {
        return view('housing/housingapplication.show', compact('id'));
    }

    public function edit($id)
    {
        return view('housing/housingapplication.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Deleted successfully');
    }
}
