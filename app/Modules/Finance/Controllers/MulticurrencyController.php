<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MulticurrencyController extends Controller
{
    public function index()
    {
        return view('finance/multicurrency.index');
    }

    public function create()
    {
        return view('finance/multicurrency.create');
    }

    public function store(Request $request)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function show($id)
    {
        return view('finance/multicurrency.show', compact('id'));
    }

    public function edit($id)
    {
        return view('finance/multicurrency.edit', compact('id'));
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
