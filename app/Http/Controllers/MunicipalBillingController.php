<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing\MunicipalBill;
use App\Models\Billing\CustomerAccount;
use App\Models\Billing\MunicipalService;

class MunicipalBillingController extends Controller
{
    public function index()
    {
        $bills = MunicipalBill::with(['customer', 'service'])->latest()->paginate(15);
        $totalOutstanding = MunicipalBill::where('status', 'pending')->sum('total_amount');
        $totalPaid = MunicipalBill::where('status', 'paid')->sum('total_amount');
        
        return view('billing.index', compact('bills', 'totalOutstanding', 'totalPaid'));
    }

    public function create()
    {
        $customers = CustomerAccount::where('is_active', true)->get();
        $services = MunicipalService::where('is_active', true)->get();
        
        return view('billing.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_account_id' => 'required|exists:customer_accounts,id',
            'service_id' => 'required|exists:municipal_services,id',
            'billing_period_start' => 'required|date',
            'billing_period_end' => 'required|date|after:billing_period_start',
            'usage_amount' => 'required|numeric|min:0',
            'rate_per_unit' => 'required|numeric|min:0',
            'base_charge' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $validated['total_amount'] = ($validated['usage_amount'] * $validated['rate_per_unit']) + $validated['base_charge'];
        $validated['status'] = 'pending';
        $validated['bill_number'] = 'BILL-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        MunicipalBill::create($validated);

        return redirect()->route('billing.index')
            ->with('success', 'Municipal bill created successfully.');
    }

    public function show(MunicipalBill $bill)
    {
        $bill->load(['customer', 'service', 'payments']);
        return view('billing.show', compact('bill'));
    }

    public function markPaid(MunicipalBill $bill)
    {
        $bill->update(['status' => 'paid', 'paid_date' => now()]);
        
        return redirect()->back()
            ->with('success', 'Bill marked as paid successfully.');
    }
}
