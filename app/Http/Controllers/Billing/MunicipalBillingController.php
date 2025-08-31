<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Billing\MunicipalBill;
use App\Models\Billing\CustomerAccount;
use App\Models\Billing\MunicipalService;
use App\Models\Billing\BillPayment;
use App\Models\Billing\PaymentMethod;
use App\Models\Council;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MunicipalBillingController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bills' => MunicipalBill::count(),
            'pending_bills' => MunicipalBill::where('status', 'pending')->count(),
            'overdue_bills' => MunicipalBill::overdue()->count(),
            'total_revenue' => BillPayment::where('status', 'completed')->sum('amount'),
            'outstanding_amount' => MunicipalBill::sum('outstanding_amount'),
            'monthly_revenue' => BillPayment::where('status', 'completed')
                                ->whereMonth('payment_date', now()->month)
                                ->sum('amount')
        ];

        $recent_bills = MunicipalBill::with(['customerAccount', 'council'])
                        ->latest()
                        ->limit(10)
                        ->get();

        $overdue_bills = MunicipalBill::with(['customerAccount'])
                        ->overdue()
                        ->orderBy('due_date')
                        ->limit(10)
                        ->get();

        return view('billing.index', compact('stats', 'recent_bills', 'overdue_bills'));
    }

    // Customer Accounts
    public function accounts()
    {
        return view('billing.accounts.index');
    }

    public function customers()
    {
        $customers = CustomerAccount::with('council')->paginate(20);
        return view('billing.customers.index', compact('customers'));
    }

    public function createCustomer()
    {
        $councils = Council::where('active', true)->get();
        return view('billing.customers.create', compact('councils'));
    }

    // Bills Management - removed duplicate createBill method

    // Payments Management
    public function payments()
    {
        return view('billing.payments.index');
    }

    public function createPayment()
    {
        return view('billing.payments.create');
    }

    public function storePayment(Request $request)
    {
        // Store payment logic here
        return redirect()->route('billing.payments.index')->with('success', 'Payment recorded successfully');
    }

    // Reports
    public function reports()
    {
        return view('billing.reports.index');
    }

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:individual,business,organization',
            'customer_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'physical_address' => 'required|string',
            'postal_address' => 'nullable|string',
            'id_number' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'council_id' => 'required|exists:councils,id'
        ]);

        // Generate unique account number
        $accountNumber = 'ACC-' . now()->format('Y') . '-' . str_pad(CustomerAccount::count() + 1, 6, '0', STR_PAD_LEFT);
        $validated['account_number'] = $accountNumber;

        // Ensure credit_limit has a default value if not provided
        $validated['credit_limit'] = $validated['credit_limit'] ?? 0.00;

        CustomerAccount::create($validated);

        return redirect()->route('billing.customers.index')->with('success', 'Customer created successfully.');
    }

    public function createAccount()
    {
        return view('billing.accounts.create');
    }

    public function storeAccount(Request $request)
    {
        // Store account logic here
        return redirect()->route('billing.accounts.index')->with('success', 'Account created successfully');
    }

    public function showAccount($id)
    {
        return view('billing.accounts.show', compact('id'));
    }

    public function editAccount($id)
    {
        return view('billing.accounts.edit', compact('id'));
    }

    public function updateAccount(Request $request, $id)
    {
        // Update account logic here
        return redirect()->route('billing.accounts.index')->with('success', 'Account updated successfully');
    }

    public function destroyAccount($id)
    {
        // Delete account logic here
        return redirect()->route('billing.accounts.index')->with('success', 'Account deleted successfully');
    }

    // Services
    public function services()
    {
        return view('billing.services.index');
    }

    public function createService()
    {
        return view('billing.services.create');
    }

    public function storeService(Request $request)
    {
        // Store service logic here
        return redirect()->route('billing.services.index')->with('success', 'Service created successfully');
    }

    public function showService($id)
    {
        return view('billing.services.show', compact('id'));
    }

    public function editService($id)
    {
        return view('billing.services.edit', compact('id'));
    }

    public function updateService(Request $request, $id)
    {
        // Update service logic here
        return redirect()->route('billing.services.index')->with('success', 'Service updated successfully');
    }

    public function destroyService($id)
    {
        // Delete service logic here
        return redirect()->route('billing.services.index')->with('success', 'Service deleted successfully');
    }

    // Bills
    // Customer Management Methods
    public function showCustomer($id)
    {
        $customer = CustomerAccount::findOrFail($id);
        return view('billing.customers.show', compact('customer'));
    }

    public function editCustomer($id)
    {
        $customer = CustomerAccount::findOrFail($id);
        $councils = Council::where('active', true)->get();
        return view('billing.customers.edit', compact('customer', 'councils'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:individual,business,organization',
            'customer_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'physical_address' => 'required|string',
            'postal_address' => 'nullable|string',
            'id_number' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'council_id' => 'required|exists:councils,id'
        ]);

        $customer = CustomerAccount::findOrFail($id);
        $customer->update($validated);

        return redirect()->route('billing.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroyCustomer($id)
    {
        $customer = CustomerAccount::findOrFail($id);
        $customer->delete();
        return redirect()->route('billing.customers.index')->with('success', 'Customer deleted successfully.');
    }



    // Bill Management Methods  
    public function editBill($id)
    {
        $bill = MunicipalBill::with(['customerAccount', 'lineItems.service'])->findOrFail($id);
        return view('billing.bills.edit', compact('bill'));
    }

    public function updateBill(Request $request, $id)
    {
        // Update bill logic here
        return redirect()->route('billing.bills.index')->with('success', 'Bill updated successfully');
    }

    public function destroyBill($id)
    {
        $bill = MunicipalBill::findOrFail($id);
        $bill->delete();
        return redirect()->route('billing.bills.index')->with('success', 'Bill deleted successfully');
    }

    public function showPayment($id)
    {
        return view('billing.payments.show', compact('id'));
    }

    public function revenueReport()
    {
        return view('billing.reports.revenue');
    }

    public function outstandingReport()
    {
        return view('billing.reports.outstanding');
    }

    public function collectionsReport()
    {
        return view('billing.reports.collections');
    }

    public function bills()
    {
        $bills = MunicipalBill::with(['customerAccount', 'council'])
                ->when(request('status'), function($query) {
                    $query->where('status', request('status'));
                })
                ->when(request('customer'), function($query) {
                    $query->whereHas('customerAccount', function($q) {
                        $q->where('customer_name', 'like', '%' . request('customer') . '%');
                    });
                })
                ->orderBy('bill_date', 'desc')
                ->paginate(20);

        return view('billing.bills.index', compact('bills'));
    }

    public function createBill()
    {
        $customers = CustomerAccount::active()->get();
        $services = MunicipalService::active()->with('category')->get();
        $councils = Council::where('active', true)->get();

        return view('billing.bills.create', compact('customers', 'services', 'councils'));
    }

    public function storeBill(Request $request)
    {
        $validated = $request->validate([
            'customer_account_id' => 'required|exists:customer_accounts,id',
            'billing_period' => 'required|string',
            'due_date' => 'required|date|after:today',
            'services' => 'required|array',
            'services.*.service_id' => 'required|exists:municipal_services,id',
            'services.*.quantity' => 'required|numeric|min:1',
            'services.*.custom_rate' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated) {
            $billNumber = 'BILL-' . now()->format('Y') . '-' . str_pad(MunicipalBill::count() + 1, 6, '0', STR_PAD_LEFT);

            $bill = MunicipalBill::create([
                'bill_number' => $billNumber,
                'customer_account_id' => $validated['customer_account_id'],
                'bill_date' => now(),
                'due_date' => $validated['due_date'],
                'billing_period' => $validated['billing_period'],
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'notes' => $validated['notes'],
                'status' => 'pending',
                'council_id' => CustomerAccount::find($validated['customer_account_id'])->council_id,
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'outstanding_amount' => 0
            ]);

            foreach ($validated['services'] as $serviceData) {
                $service = MunicipalService::find($serviceData['service_id']);
                $rate = $serviceData['custom_rate'] ?? $service->base_rate;
                $amount = $rate * $serviceData['quantity'];
                $taxAmount = $service->calculateTaxAmount($amount);

                $bill->lineItems()->create([
                    'service_id' => $service->id,
                    'description' => $service->name,
                    'quantity' => $serviceData['quantity'],
                    'unit_rate' => $rate,
                    'amount' => $amount,
                    'tax_amount' => $taxAmount
                ]);
            }

            $bill->calculateTotals();
        });

        return redirect()->route('billing.bills')->with('success', 'Bill created successfully.');
    }

    public function showBill($id)
    {
        $bill = MunicipalBill::with(['customerAccount', 'lineItems.service', 'payments.paymentMethod'])
                ->findOrFail($id);

        return view('billing.bills.show', compact('bill'));
    }
}