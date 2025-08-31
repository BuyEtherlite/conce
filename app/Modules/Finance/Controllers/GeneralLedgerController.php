<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    public function index()
    {
        return view('finance/generalledger.index');
    }

    public function create()
    {
        return view('finance/generalledger.create');
    }

    public function store(Request $request)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function show($id)
    {
        return view('finance/generalledger.show', compact('id'));
    }

    public function edit($id)
    {
        return view('finance/generalledger.edit', compact('id'));
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
