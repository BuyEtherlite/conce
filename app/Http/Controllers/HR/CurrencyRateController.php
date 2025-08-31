<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index()
    {
        return view('hr.currency.rates.index');
    }

    public function create()
    {
        return view('hr.currency.rates.create');
    }

    public function store(Request $request)
    {
        // Implementation here
        return redirect()->route('hr.currency.rates.index');
    }

    public function show($rate)
    {
        return view('hr.currency.rates.show', compact('rate'));
    }

    public function edit($rate)
    {
        return view('hr.currency.rates.edit', compact('rate'));
    }

    public function update(Request $request, $rate)
    {
        // Implementation here
        return redirect()->route('hr.currency.rates.index');
    }

    public function destroy($rate)
    {
        // Implementation here
        return redirect()->route('hr.currency.rates.index');
    }
}
