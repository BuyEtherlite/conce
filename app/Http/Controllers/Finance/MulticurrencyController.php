<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MulticurrencyController extends Controller
{
    public function index()
    {
        try {
            $currencies = Currency::all();
            return view('finance.multicurrency.index', compact('currencies'));
        } catch (\Exception $e) {
            Log::error('Multicurrency index error: ' . $e->getMessage());
            return view('finance.multicurrency.index', ['currencies' => collect()]);
        }
    }

    public function create()
    {
        return view('finance.multicurrency.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:3|unique:currencies',
                'name' => 'required|string|max:255',
                'symbol' => 'required|string|max:10',
                'exchange_rate' => 'required|numeric|min:0',
            ]);

            Currency::create($validated);

            return redirect()->route('finance.multicurrency.index')
                ->with('success', 'Currency created successfully.');
        } catch (\Exception $e) {
            Log::error('Currency creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create currency: ' . $e->getMessage());
        }
    }

    public function show($currency)
    {
        try {
            $currency = Currency::findOrFail($currency);
            return view('finance.multicurrency.show', compact('currency'));
        } catch (\Exception $e) {
            return redirect()->route('finance.multicurrency.index')
                ->with('error', 'Currency not found.');
        }
    }

    public function edit($currency)
    {
        try {
            $currency = Currency::findOrFail($currency);
            return view('finance.multicurrency.edit', compact('currency'));
        } catch (\Exception $e) {
            return redirect()->route('finance.multicurrency.index')
                ->with('error', 'Currency not found.');
        }
    }

    public function update(Request $request, $currency)
    {
        try {
            $currency = Currency::findOrFail($currency);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'symbol' => 'required|string|max:10',
                'exchange_rate' => 'required|numeric|min:0',
            ]);

            $currency->update($validated);

            return redirect()->route('finance.multicurrency.index')
                ->with('success', 'Currency updated successfully.');
        } catch (\Exception $e) {
            Log::error('Currency update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update currency: ' . $e->getMessage());
        }
    }

    public function destroy($currency)
    {
        try {
            $currency = Currency::findOrFail($currency);
            $currency->delete();

            return redirect()->route('finance.multicurrency.index')
                ->with('success', 'Currency deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('finance.multicurrency.index')
                ->with('error', 'Failed to delete currency: ' . $e->getMessage());
        }
    }

    public function rates()
    {
        try {
            $currencies = Currency::all();
            return view('finance.multicurrency.rates', compact('currencies'));
        } catch (\Exception $e) {
            return view('finance.multicurrency.rates', ['currencies' => collect()]);
        }
    }

    public function storeRate(Request $request)
    {
        try {
            $validated = $request->validate([
                'currency_id' => 'required|exists:currencies,id',
                'rate' => 'required|numeric|min:0',
                'effective_date' => 'required|date',
            ]);

            // Update the currency exchange rate
            $currency = Currency::findOrFail($validated['currency_id']);
            $currency->update([
                'exchange_rate' => $validated['rate'],
                'updated_at' => now(),
            ]);

            return redirect()->route('finance.multicurrency.rates')
                ->with('success', 'Exchange rate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update rate: ' . $e->getMessage());
        }
    }

    public function converter()
    {
        try {
            $currencies = Currency::all();
            return view('finance.multicurrency.converter', compact('currencies'));
        } catch (\Exception $e) {
            return view('finance.multicurrency.converter', ['currencies' => collect()]);
        }
    }

    public function convert(Request $request)
    {
        try {
            $validated = $request->validate([
                'from_currency' => 'required|exists:currencies,id',
                'to_currency' => 'required|exists:currencies,id',
                'amount' => 'required|numeric|min:0',
            ]);

            $fromCurrency = Currency::findOrFail($validated['from_currency']);
            $toCurrency = Currency::findOrFail($validated['to_currency']);

            // Convert amount using exchange rates
            $convertedAmount = ($validated['amount'] / $fromCurrency->exchange_rate) * $toCurrency->exchange_rate;

            return response()->json([
                'success' => true,
                'converted_amount' => round($convertedAmount, 2),
                'from' => $fromCurrency->code,
                'to' => $toCurrency->code,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function transactions()
    {
        // Placeholder for multicurrency transactions
        return view('finance.multicurrency.transactions', ['transactions' => collect()]);
    }

    public function reports()
    {
        try {
            $currencies = Currency::all();
            return view('finance.multicurrency.reports', compact('currencies'));
        } catch (\Exception $e) {
            return view('finance.multicurrency.reports', ['currencies' => collect()]);
        }
    }

    public function revaluation()
    {
        try {
            $currencies = Currency::all();
            return view('finance.multicurrency.revaluation', compact('currencies'));
        } catch (\Exception $e) {
            return view('finance.multicurrency.revaluation', ['currencies' => collect()]);
        }
    }
}
