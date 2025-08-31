<?php

namespace App\Http\Controllers\CouncilAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('counciladmin/user.index');
    }

    public function create()
    {
        return view('counciladmin/user.create');
    }

    public function store(Request $request)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function show($id)
    {
        return view('counciladmin/user.show', compact('id'));
    }

    public function edit($id)
    {
        return view('counciladmin/user.edit', compact('id'));
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
