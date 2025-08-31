<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Models\GeneralLedger;
use App\Modules\Finance\Models\ChartOfAccount;

class GeneralLedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GeneralLedger::with('account');
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }
        
        // Filter by account
        if ($request->filled('account_code')) {
            $query->where('account_code', $request->account_code);
        }
        
        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        
        $entries = $query->orderBy('transaction_date', 'desc')
                         ->orderBy('transaction_number', 'desc')
                         ->paginate(50);
        
        $accounts = ChartOfAccount::active()->orderBy('account_code')->get();
        
        return view('finance.general-ledger.index', compact('entries', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = ChartOfAccount::active()->orderBy('account_code')->get();
        
        return view('finance.general-ledger.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_number' => 'required|string|max:255|unique:finance_general_ledger',
            'account_code' => 'required|exists:finance_chart_of_accounts,account_code',
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|string|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'reference_number' => 'nullable|string|max:255',
            'source_document' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();

        GeneralLedger::create($data);

        return redirect()->route('finance.general-ledger.index')
            ->with('success', 'General ledger entry created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entry = GeneralLedger::with(['account', 'createdBy'])->findOrFail($id);
        
        return view('finance.general-ledger.show', compact('entry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $entry = GeneralLedger::findOrFail($id);
        $accounts = ChartOfAccount::active()->orderBy('account_code')->get();
        
        return view('finance.general-ledger.edit', compact('entry', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $entry = GeneralLedger::findOrFail($id);
        
        $request->validate([
            'transaction_number' => 'required|string|max:255|unique:finance_general_ledger,transaction_number,' . $entry->id,
            'account_code' => 'required|exists:finance_chart_of_accounts,account_code',
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|string|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'reference_number' => 'nullable|string|max:255',
            'source_document' => 'nullable|string|max:255',
        ]);

        $entry->update($request->all());

        return redirect()->route('finance.general-ledger.index')
            ->with('success', 'General ledger entry updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $entry = GeneralLedger::findOrFail($id);
        $entry->delete();

        return redirect()->route('finance.general-ledger.index')
            ->with('success', 'General ledger entry deleted successfully');
    }

    /**
     * Generate trial balance report.
     */
    public function trialBalance(Request $request)
    {
        $request->validate([
            'as_of_date' => 'required|date',
        ]);

        $asOfDate = $request->as_of_date;
        
        $trialBalance = GeneralLedger::selectRaw('
                account_code,
                SUM(CASE WHEN transaction_type = "debit" THEN amount ELSE 0 END) as total_debits,
                SUM(CASE WHEN transaction_type = "credit" THEN amount ELSE 0 END) as total_credits
            ')
            ->where('transaction_date', '<=', $asOfDate)
            ->groupBy('account_code')
            ->with('account')
            ->get();

        return view('finance.general-ledger.trial-balance', compact('trialBalance', 'asOfDate'));
    }

    /**
     * Generate account ledger report.
     */
    public function accountLedger(Request $request)
    {
        $request->validate([
            'account_code' => 'required|exists:finance_chart_of_accounts,account_code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $account = ChartOfAccount::where('account_code', $request->account_code)->first();
        
        $ledgerEntries = GeneralLedger::where('account_code', $request->account_code)
            ->whereBetween('transaction_date', [$request->start_date, $request->end_date])
            ->orderBy('transaction_date')
            ->orderBy('transaction_number')
            ->get();

        // Calculate running balance
        $runningBalance = 0;
        $ledgerEntries->each(function ($entry) use (&$runningBalance) {
            if ($entry->transaction_type === 'debit') {
                $runningBalance += $entry->amount;
            } else {
                $runningBalance -= $entry->amount;
            }
            $entry->running_balance = $runningBalance;
        });

        return view('finance.general-ledger.account-ledger', compact(
            'account', 'ledgerEntries', 'request'
        ));
    }

    /**
     * Export general ledger to Excel/CSV.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        // Export logic would go here
        
        return response()->json([
            'success' => true,
            'message' => 'Export will be available shortly'
        ]);
    }
}