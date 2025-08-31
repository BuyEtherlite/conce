<?php

namespace App\Modules\HR\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HR\Models\CurrencyRate;
use App\Models\Finance\Currency;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index()
    {
        $rates = CurrencyRate::with(['currency', 'creator'])
            ->latest('effective_date')
            ->paginate(20);

        return view('hr.currency.rates.index', compact('rates'));
    }

    public function create()
    {
        $currencies = Currency::where('is_active', true)->get();
        return view('hr.currency.rates.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'exchange_rate' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'rate_type' => 'required|in:buy,sell,mid',
        ]);

        CurrencyRate::create([
            'currency_id' => $request->currency_id,
            'exchange_rate' => $request->exchange_rate,
            'effective_date' => $request->effective_date,
            'rate_type' => $request->rate_type,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('hr.currency.rates.index')
            ->with('success', 'Currency rate created successfully.');
    }

    public function show(CurrencyRate $rate)
    {
        $rate->load(['currency', 'creator']);
        return view('hr.currency.rates.show', compact('rate'));
    }

    public function edit(CurrencyRate $rate)
    {
        $currencies = Currency::where('is_active', true)->get();
        return view('hr.currency.rates.edit', compact('rate', 'currencies'));
    }

    public function update(Request $request, CurrencyRate $rate)
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'exchange_rate' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'rate_type' => 'required|in:buy,sell,mid',
            'is_active' => 'boolean',
        ]);

        $rate->update($request->all());

        return redirect()->route('hr.currency.rates.index')
            ->with('success', 'Currency rate updated successfully.');
    }

    public function destroy(CurrencyRate $rate)
    {
        $rate->delete();

        return redirect()->route('hr.currency.rates.index')
            ->with('success', 'Currency rate deleted successfully.');
    }
}