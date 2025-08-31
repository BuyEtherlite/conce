<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Implement index method
        return view('finance.asset.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Implement create method
        return view('finance.asset.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement store method
        $request->validate([
            // Add validation rules here
        ]);

        // Store logic here

        return redirect()->route('finance.asset.index')
            ->with('success', 'AssetController created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Implement show method
        return view('finance.asset.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Implement edit method
        return view('finance.asset.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update method
        $request->validate([
            // Add validation rules here
        ]);

        // Update logic here

        return redirect()->route('finance.asset.index')
            ->with('success', 'AssetController updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement destroy method
        // Delete logic here

        return redirect()->route('finance.asset.index')
            ->with('success', 'AssetController deleted successfully.');
    }
}
