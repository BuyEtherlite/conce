<?php

namespace App\Modules\Housing\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    public function index()
    {
        return view('housing/waitinglist.index');
    }

    public function create()
    {
        return view('housing/waitinglist.create');
    }

    public function store(Request $request)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function show($id)
    {
        return view('housing/waitinglist.show', compact('id'));
    }

    public function edit($id)
    {
        return view('housing/waitinglist.edit', compact('id'));
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
