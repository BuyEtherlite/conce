<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use App\Models\PropertyTax\PropertyValuation;
use App\Models\PropertyTax\PropertyTaxAssessment;
use App\Models\PropertyTax\PropertyTaxBill;
use App\Models\PropertyTax\PropertyTaxPayment;
use App\Models\PropertyTax\PropertyTaxCategory;
use App\Models\PropertyTax\PropertyTaxZone;
use App\Models\PropertyTax\PropertyTaxExemption;
use App\Models\PropertyTax\PropertyTaxAppeal;
use App\Models\Council;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyTaxationController extends Controller
{
    public function index()
    {
        $stats = [
            'total_valuations' => PropertyValuation::count(),
            'active_valuations' => PropertyValuation::where('status', 'active')->count(),
            'total_assessments' => PropertyTaxAssessment::count(),
            'current_year_assessments' => PropertyTaxAssessment::where('tax_year', date('Y'))->count(),
            'total_bills' => PropertyTaxBill::count(),
            'unpaid_bills' => PropertyTaxBill::where('status', '!=', 'paid')->count(),
            'overdue_bills' => PropertyTaxBill::overdue()->count(),
            'total_revenue' => PropertyTaxPayment::where('status', 'completed')->sum('amount_paid'),
            'outstanding_amount' => PropertyTaxBill::sum('outstanding_amount'),
            'current_year_revenue' => PropertyTaxPayment::where('status', 'completed')
                                        ->whereYear('payment_date', date('Y'))
                                        ->sum('amount_paid')
        ];

        $recent_payments = PropertyTaxPayment::with(['bill.assessment.valuation'])
                            ->where('status', 'completed')
                            ->latest()
                            ->limit(5)
                            ->get();

        $overdue_bills = PropertyTaxBill::with(['assessment.valuation'])
                            ->overdue()
                            ->orderBy('payment_due_date')
                            ->limit(10)
                            ->get();

        return view('property-tax.index', compact('stats', 'recent_payments', 'overdue_bills'));
    }

    // Valuations Management
    public function valuations()
    {
        $valuations = PropertyValuation::with(['taxCategory', 'taxZone', 'council'])
                        ->paginate(15);

        $categories = PropertyTaxCategory::active()->get();
        $zones = PropertyTaxZone::active()->get();
        $councils = Council::where('is_active', true)->get();

        return view('property-tax.valuations.index', compact('valuations', 'categories', 'zones', 'councils'));
    }

    public function createValuation()
    {
        $categories = PropertyTaxCategory::active()->get();
        $zones = PropertyTaxZone::active()->get();
        $councils = Council::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('property-tax.valuations.create', compact('categories', 'zones', 'councils', 'departments'));
    }

    public function storeValuation(Request $request)
    {
        $validated = $request->validate([
            'valuation_number' => 'required|string|unique:property_valuations,valuation_number',
            'property_code' => 'required|string',
            'owner_name' => 'required|string|max:255',
            'owner_id_number' => 'required|string|max:50',
            'property_address' => 'required|string',
            'suburb' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'land_size' => 'required|numeric|min:0',
            'building_size' => 'nullable|numeric|min:0',
            'property_type' => 'required|string',
            'land_use' => 'required|string',
            'market_value' => 'required|numeric|min:0',
            'municipal_value' => 'required|numeric|min:0',
            'improvements_value' => 'nullable|numeric|min:0',
            'valuation_date' => 'required|date',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'valuer_name' => 'required|string|max:255',
            'valuation_method' => 'required|string',
            'valuation_notes' => 'nullable|string',
            'tax_category_id' => 'required|exists:property_tax_categories,id',
            'tax_zone_id' => 'required|exists:property_tax_zones,id',
            'council_id' => 'required|exists:councils,id',
            'department_id' => 'required|exists:departments,id'
        ]);

        $validated['improvements_value'] = $validated['improvements_value'] ?? 0;

        PropertyValuation::create($validated);

        return redirect()->route('property-tax.valuations')
            ->with('success', 'Property valuation created successfully.');
    }

    // Assessments Management
    public function assessments()
    {
        $assessments = PropertyTaxAssessment::with(['valuation', 'assessor'])
                        ->latest()
                        ->paginate(15);

        return view('property-tax.assessments.index', compact('assessments'));
    }

    public function createAssessment()
    {
        $valuations = PropertyValuation::active()
                        ->whereDoesntHave('assessments', function($query) {
                            $query->where('tax_year', date('Y'));
                        })
                        ->get();

        return view('property-tax.assessments.create', compact('valuations'));
    }

    public function storeAssessment(Request $request)
    {
        $validated = $request->validate([
            'assessment_number' => 'required|string|unique:property_tax_assessments,assessment_number',
            'valuation_id' => 'required|exists:property_valuations,id',
            'tax_year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'taxable_value' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0',
            'base_tax_amount' => 'required|numeric|min:0',
            'zone_adjustment' => 'nullable|numeric',
            'exemptions_amount' => 'nullable|numeric|min:0',
            'penalties_amount' => 'nullable|numeric|min:0',
            'interest_amount' => 'nullable|numeric|min:0',
            'assessment_date' => 'required|date',
            'due_date' => 'required|date|after:assessment_date',
            'assessment_notes' => 'nullable|string'
        ]);

        $validated['zone_adjustment'] = $validated['zone_adjustment'] ?? 0;
        $validated['exemptions_amount'] = $validated['exemptions_amount'] ?? 0;
        $validated['penalties_amount'] = $validated['penalties_amount'] ?? 0;
        $validated['interest_amount'] = $validated['interest_amount'] ?? 0;
        $validated['assessed_by'] = auth()->id();

        // Calculate total tax amount
        $validated['total_tax_amount'] = $validated['base_tax_amount'] + 
                                       $validated['zone_adjustment'] + 
                                       $validated['penalties_amount'] + 
                                       $validated['interest_amount'] - 
                                       $validated['exemptions_amount'];

        PropertyTaxAssessment::create($validated);

        return redirect()->route('property-tax.assessments')
            ->with('success', 'Tax assessment created successfully.');
    }

    // Bills Management
    public function bills()
    {
        $bills = PropertyTaxBill::with(['assessment.valuation'])
                    ->latest()
                    ->paginate(15);

        return view('property-tax.bills.index', compact('bills'));
    }

    public function generateBill($assessmentId)
    {
        $assessment = PropertyTaxAssessment::with('valuation')->findOrFail($assessmentId);

        // Check if bill already exists
        if ($assessment->bills()->exists()) {
            return back()->with('error', 'Bill already exists for this assessment.');
        }

        $billNumber = 'BILL-' . date('Y') . '-' . str_pad($assessment->id, 6, '0', STR_PAD_LEFT);

        $bill = PropertyTaxBill::create([
            'bill_number' => $billNumber,
            'assessment_id' => $assessment->id,
            'bill_date' => now()->toDateString(),
            'due_date' => $assessment->due_date,
            'principal_amount' => $assessment->total_tax_amount,
            'interest_amount' => 0,
            'penalty_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => $assessment->total_tax_amount,
            'paid_amount' => 0,
            'outstanding_amount' => $assessment->total_tax_amount,
            'payment_due_date' => $assessment->due_date,
            'status' => 'unpaid'
        ]);

        return redirect()->route('property-tax.bills')
            ->with('success', 'Tax bill generated successfully.');
    }

    // Payments Management
    public function payments()
    {
        $payments = PropertyTaxPayment::with(['bill.assessment.valuation', 'processor'])
                    ->latest()
                    ->paginate(15);

        return view('property-tax.payments.index', compact('payments'));
    }

    public function recordPayment()
    {
        $unpaidBills = PropertyTaxBill::with(['assessment.valuation'])
                        ->where('status', '!=', 'paid')
                        ->get();

        return view('property-tax.payments.create', compact('unpaidBills'));
    }

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_reference' => 'required|string|unique:property_tax_payments,payment_reference',
            'bill_id' => 'required|exists:property_tax_bills,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'transaction_reference' => 'nullable|string',
            'receipt_number' => 'nullable|string',
            'payment_notes' => 'nullable|string'
        ]);

        $bill = PropertyTaxBill::findOrFail($validated['bill_id']);

        // Validate payment amount doesn't exceed outstanding amount
        if ($validated['amount_paid'] > $bill->outstanding_amount) {
            return back()->withErrors(['amount_paid' => 'Payment amount cannot exceed outstanding amount.']);
        }

        DB::transaction(function () use ($validated, $bill) {
            // Create payment record
            $validated['processed_by'] = auth()->id();
            $validated['status'] = 'completed';

            PropertyTaxPayment::create($validated);

            // Update bill amounts
            $bill->paid_amount += $validated['amount_paid'];
            $bill->outstanding_amount -= $validated['amount_paid'];

            if ($bill->outstanding_amount <= 0) {
                $bill->status = 'paid';
                $bill->outstanding_amount = 0;
            }

            $bill->save();
        });

        return redirect()->route('property-tax.payments')
            ->with('success', 'Payment recorded successfully.');
    }

    // Reports
    public function reports()
    {
        return view('property-tax.reports.index');
    }

    public function revenueReport(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $monthlyRevenue = PropertyTaxPayment::select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount_paid) as total')
            )
            ->whereYear('payment_date', $year)
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $totalRevenue = PropertyTaxPayment::whereYear('payment_date', $year)
                        ->where('status', 'completed')
                        ->sum('amount_paid');

        $outstandingAmount = PropertyTaxBill::sum('outstanding_amount');

        return view('property-tax.reports.revenue', compact('monthlyRevenue', 'totalRevenue', 'outstandingAmount', 'year'));
    }

    public function collectionReport()
    {
        $overdueBills = PropertyTaxBill::with(['assessment.valuation'])
                        ->overdue()
                        ->orderBy('payment_due_date')
                        ->get();

        $collectionStats = [
            'total_bills' => PropertyTaxBill::count(),
            'paid_bills' => PropertyTaxBill::where('status', 'paid')->count(),
            'overdue_bills' => PropertyTaxBill::overdue()->count(),
            'collection_rate' => PropertyTaxBill::count() > 0 ? 
                (PropertyTaxBill::where('status', 'paid')->count() / PropertyTaxBill::count()) * 100 : 0
        ];

        return view('property-tax.reports.collection', compact('overdueBills', 'collectionStats'));
    }
}