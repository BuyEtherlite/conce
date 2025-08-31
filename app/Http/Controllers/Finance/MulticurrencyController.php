<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\Currency;
use App\Modules\Finance\Models\ExchangeRateHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MulticurrencyController extends Controller
{
    public function index()
    {
        try {
            $currencies = Currency::with(['exchangeRateHistories' => function($query) {
                $query->orderBy('effective_date', 'desc')->limit(5);
            }])->get();
            
            // Get today's exchange rate updates
            $todaysUpdates = ExchangeRateHistory::whereDate('created_at', today())->count();
            
            return view('finance.multicurrency.index', compact('currencies', 'todaysUpdates'));
        } catch (\Exception $e) {
            Log::error('Multicurrency index error: ' . $e->getMessage());
            return view('finance.multicurrency.index', ['currencies' => collect(), 'todaysUpdates' => 0]);
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
                'is_base_currency' => 'boolean',
                'auto_update' => 'boolean',
                'rounding_precision' => 'integer|min:0|max:10'
            ]);

            // If this is set as base currency, unset others
            if ($request->has('is_base_currency') && $request->is_base_currency) {
                Currency::where('is_base_currency', true)->update(['is_base_currency' => false]);
                $validated['exchange_rate'] = 1.0; // Base currency always has rate of 1
            }

            $currency = Currency::create($validated);

            // Create initial exchange rate history
            ExchangeRateHistory::create([
                'currency_id' => $currency->id,
                'exchange_rate' => $currency->exchange_rate,
                'effective_date' => now(),
                'source' => 'manual',
                'is_active' => true,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('finance.multicurrency.index')
                ->with('success', 'Currency created successfully.');
        } catch (\Exception $e) {
            Log::error('Currency creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create currency: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $currency = Currency::with('exchangeRateHistories')->findOrFail($id);
            return view('finance.multicurrency.edit', compact('currency'));
        } catch (\Exception $e) {
            return redirect()->route('finance.multicurrency.index')
                ->with('error', 'Currency not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $currency = Currency::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'symbol' => 'required|string|max:10',
                'exchange_rate' => 'required|numeric|min:0',
                'is_base_currency' => 'boolean',
                'auto_update' => 'boolean',
                'rounding_precision' => 'integer|min:0|max:10'
            ]);

            $oldRate = $currency->exchange_rate;

            // Handle base currency logic
            if ($request->has('is_base_currency') && $request->is_base_currency) {
                Currency::where('is_base_currency', true)->update(['is_base_currency' => false]);
                $validated['exchange_rate'] = 1.0;
            }

            $currency->update($validated);

            // Create exchange rate history if rate changed
            if ($oldRate != $validated['exchange_rate']) {
                ExchangeRateHistory::create([
                    'currency_id' => $currency->id,
                    'exchange_rate' => $validated['exchange_rate'],
                    'effective_date' => now(),
                    'source' => 'manual',
                    'is_active' => true,
                    'created_by' => auth()->id()
                ]);
            }

            return redirect()->route('finance.multicurrency.index')
                ->with('success', 'Currency updated successfully.');
        } catch (\Exception $e) {
            Log::error('Currency update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update currency: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $currency = Currency::findOrFail($id);
            
            // Prevent deletion of base currency
            if ($currency->is_base_currency) {
                return redirect()->back()
                    ->with('error', 'Cannot delete base currency.');
            }

            // Check if currency is used in transactions
            $usageCount = $this->checkCurrencyUsage($currency->code);
            if ($usageCount > 0) {
                return redirect()->back()
                    ->with('error', "Cannot delete currency. It is used in {$usageCount} transaction(s).");
            }

            $currency->delete();

            return redirect()->route('finance.multicurrency.index')
                ->with('success', 'Currency deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Currency deletion error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete currency.');
        }
    }

    /**
     * Convert between currencies
     */
    public function convert(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0',
                'from_currency' => 'required|exists:currencies,id',
                'to_currency' => 'required|exists:currencies,id',
            ]);

            $fromCurrency = Currency::findOrFail($validated['from_currency']);
            $toCurrency = Currency::findOrFail($validated['to_currency']);

            // Get latest exchange rates
            $fromRate = $this->getLatestExchangeRate($fromCurrency);
            $toRate = $this->getLatestExchangeRate($toCurrency);

            // Convert to base currency first, then to target currency
            $baseAmount = $validated['amount'] / $fromRate;
            $convertedAmount = $baseAmount * $toRate;

            // Apply rounding precision
            $precision = $toCurrency->rounding_precision ?? 2;
            $convertedAmount = round($convertedAmount, $precision);

            return response()->json([
                'success' => true,
                'converted_amount' => $convertedAmount,
                'from' => [
                    'code' => $fromCurrency->code,
                    'symbol' => $fromCurrency->symbol,
                    'rate' => $fromRate
                ],
                'to' => [
                    'code' => $toCurrency->code,
                    'symbol' => $toCurrency->symbol,
                    'rate' => $toRate
                ],
                'conversion_rate' => $toRate / $fromRate,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Currency conversion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update exchange rates from external API
     */
    public function updateExchangeRates(Request $request)
    {
        try {
            $currencies = Currency::where('auto_update', true)
                ->where('is_base_currency', false)
                ->get();

            $updated = 0;
            $errors = [];

            foreach ($currencies as $currency) {
                try {
                    $newRate = $this->fetchExchangeRateFromAPI($currency->code);
                    
                    if ($newRate && $newRate != $currency->exchange_rate) {
                        // Update currency
                        $currency->update(['exchange_rate' => $newRate]);
                        
                        // Create history record
                        ExchangeRateHistory::create([
                            'currency_id' => $currency->id,
                            'exchange_rate' => $newRate,
                            'effective_date' => now(),
                            'source' => 'api',
                            'is_active' => true,
                            'created_by' => auth()->id()
                        ]);
                        
                        $updated++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Failed to update {$currency->code}: " . $e->getMessage();
                }
            }

            $message = "Updated {$updated} exchange rates.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated_count' => $updated,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Exchange rate update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to update exchange rates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exchange rate history for a currency
     */
    public function exchangeRateHistory($currencyId)
    {
        try {
            $currency = Currency::findOrFail($currencyId);
            $history = ExchangeRateHistory::where('currency_id', $currencyId)
                ->with('creator')
                ->orderBy('effective_date', 'desc')
                ->paginate(50);

            return response()->json([
                'success' => true,
                'currency' => $currency,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch exchange rate history'
            ], 500);
        }
    }

    /**
     * Get multicurrency transactions
     */
    public function transactions(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfMonth());
            $endDate = $request->get('end_date', now()->endOfMonth());
            $currency = $request->get('currency');

            // Get transactions with foreign currency
            $transactions = $this->getMulticurrencyTransactions($startDate, $endDate, $currency);

            return view('finance.multicurrency.transactions', compact('transactions', 'startDate', 'endDate'));
        } catch (\Exception $e) {
            Log::error('Multicurrency transactions error: ' . $e->getMessage());
            return view('finance.multicurrency.transactions', ['transactions' => collect()]);
        }
    }

    /**
     * Generate multicurrency reports
     */
    public function reports(Request $request)
    {
        try {
            $reportType = $request->get('type', 'exposure');
            $currencies = Currency::all();
            $reportData = [];

            switch ($reportType) {
                case 'exposure':
                    $reportData = $this->generateExposureReport();
                    break;
                case 'variance':
                    $reportData = $this->generateVarianceReport();
                    break;
                case 'translation':
                    $reportData = $this->generateTranslationReport();
                    break;
            }

            return view('finance.multicurrency.reports', compact('currencies', 'reportData', 'reportType'));
        } catch (\Exception $e) {
            Log::error('Multicurrency reports error: ' . $e->getMessage());
            return view('finance.multicurrency.reports', ['currencies' => collect(), 'reportData' => []]);
        }
    }

    /**
     * Currency revaluation
     */
    public function revaluation(Request $request)
    {
        try {
            $currencies = Currency::where('is_base_currency', false)->get();
            $revaluationData = [];

            if ($request->isMethod('post')) {
                $revaluationData = $this->performRevaluation($request->all());
            }

            return view('finance.multicurrency.revaluation', compact('currencies', 'revaluationData'));
        } catch (\Exception $e) {
            Log::error('Currency revaluation error: ' . $e->getMessage());
            return view('finance.multicurrency.revaluation', ['currencies' => collect(), 'revaluationData' => []]);
        }
    }

    // Private helper methods

    private function getLatestExchangeRate($currency)
    {
        if ($currency->is_base_currency) {
            return 1.0;
        }

        $latestHistory = ExchangeRateHistory::where('currency_id', $currency->id)
            ->where('is_active', true)
            ->orderBy('effective_date', 'desc')
            ->first();

        return $latestHistory ? $latestHistory->exchange_rate : $currency->exchange_rate;
    }

    private function fetchExchangeRateFromAPI($currencyCode)
    {
        // Use a free exchange rate API (example: exchangerate-api.com)
        $cacheKey = "exchange_rate_{$currencyCode}_" . date('Y-m-d');
        
        return Cache::remember($cacheKey, 3600, function() use ($currencyCode) {
            try {
                $baseCurrency = Currency::where('is_base_currency', true)->first();
                $baseCurrencyCode = $baseCurrency ? $baseCurrency->code : 'USD';

                // This is a placeholder - replace with actual API
                $response = Http::timeout(10)->get("https://api.exchangerate-api.com/v4/latest/{$baseCurrencyCode}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rates'][$currencyCode] ?? null;
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error("API fetch error for {$currencyCode}: " . $e->getMessage());
                return null;
            }
        });
    }

    private function checkCurrencyUsage($currencyCode)
    {
        $count = 0;
        
        // Check various tables that might use the currency
        $tables = [
            'finance_ar_invoices' => 'currency_code',
            'finance_payments' => 'currency_code',
            'cashbook_entries' => 'currency_code',
            'fdms_receipts' => 'currency_code'
        ];

        foreach ($tables as $table => $column) {
            try {
                $count += DB::table($table)->where($column, $currencyCode)->count();
            } catch (\Exception $e) {
                // Table might not exist, continue
            }
        }

        return $count;
    }

    private function getMulticurrencyTransactions($startDate, $endDate, $currency = null)
    {
        // This would query various transaction tables
        // Placeholder implementation
        return collect();
    }

    private function generateExposureReport()
    {
        // Generate currency exposure report
        return [];
    }

    private function generateVarianceReport()
    {
        // Generate exchange rate variance report
        return [];
    }

    private function generateTranslationReport()
    {
        // Generate currency translation report
        return [];
    }

    private function performRevaluation($data)
    {
        // Perform currency revaluation
        return [];
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0'
        ]);

        try {
            $currency = Currency::findOrFail($currency);
            $currency->update($validatedData);
            
            return redirect()->route('finance.multicurrency.index')
                ->with('success', 'Currency updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update currency.');
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
            return redirect()->back()
                ->with('error', 'Failed to delete currency.');
        }
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
