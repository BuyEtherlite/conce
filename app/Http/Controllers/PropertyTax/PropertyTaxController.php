<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use App\Models\PropertyTax\PropertyTaxAssessment;
use App\Models\PropertyTax\PropertyTaxBill;
use App\Models\PropertyTax\PropertyTaxPayment;
use App\Models\PropertyTax\PropertyTaxCategory;
use App\Models\PropertyTax\PropertyTaxZone;
use App\Models\PropertyTax\PropertyValuation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyTaxController extends Controller
{
    public function index()
    {
        $stats = [
            'total_properties' => PropertyTaxAssessment::distinct('property_id')->count(),
            'total_assessments' => PropertyTaxAssessment::count(),
            'annual_revenue' => PropertyTaxPayment::whereYear('created_at', date('Y'))->sum('amount'),
            'outstanding_bills' => PropertyTaxBill::where('status', 'unpaid')->count(),
            'current_year_collected' => PropertyTaxPayment::whereYear('created_at', date('Y'))->sum('amount'),
            'collection_rate' => 85, // Calculate actual rate
            'outstanding_amount' => PropertyTaxBill::where('status', 'unpaid')->sum('amount'),
            'total_assessed_value' => PropertyTaxAssessment::sum('assessed_value'),
        ];

        $recentAssessments = PropertyTaxAssessment::with(['property'])
            ->latest()
            ->take(10)
            ->get();

        return view('property-tax.index', compact('stats', 'recentAssessments'));
    }

    public function assessments()
    {
        $assessments = PropertyTaxAssessment::with(['category', 'zone'])->paginate(20);
        $categories = PropertyTaxCategory::active()->get();
        $zones = PropertyTaxZone::active()->get();

        return view('property-tax.assessments.index', compact('assessments', 'categories', 'zones'));
    }

    public function createAssessment()
    {
        $categories = PropertyTaxCategory::active()->get();
        $zones = PropertyTaxZone::active()->get();

        return view('property-tax.assessments.create', compact('categories', 'zones'));
    }

    public function storeAssessment(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|string|max:50',
            'category_id' => 'required|exists:property_tax_categories,id',
            'zone_id' => 'required|exists:property_tax_zones,id',
            'assessed_value' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'assessment_date' => 'required|date',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string'
        ]);

        $validated['annual_tax'] = $validated['assessed_value'] * ($validated['tax_rate'] / 100);
        $validated['created_by'] = auth()->id();

        PropertyTaxAssessment::create($validated);

        return redirect()->route('property-tax.assessments.index')
            ->with('success', 'Property tax assessment created successfully.');
    }

    public function bills()
    {
        $bills = PropertyTaxBill::with(['assessment'])->paginate(20);
        return view('property-tax.bills.index', compact('bills'));
    }

    public function generateBill($assessmentId)
    {
        $assessment = PropertyTaxAssessment::findOrFail($assessmentId);

        // Check if bill already exists
        if ($assessment->bills()->exists()) {
            return back()->with('error', 'Bill already exists for this assessment.');
        }

        $bill = PropertyTaxBill::create([
            'bill_number' => 'PTB-' . date('Y') . '-' . str_pad(PropertyTaxBill::count() + 1, 6, '0', STR_PAD_LEFT),
            'assessment_id' => $assessment->id,
            'property_id' => $assessment->property_id,
            'billing_period_start' => now()->startOfYear(),
            'billing_period_end' => now()->endOfYear(),
            'base_amount' => $assessment->annual_tax,
            'penalties' => 0,
            'total_amount' => $assessment->annual_tax,
            'due_date' => now()->addDays(30),
            'status' => 'pending',
            'generated_by' => auth()->id()
        ]);

        return redirect()->route('property-tax.bills.index')
            ->with('success', 'Property tax bill generated successfully.');
    }

    public function payments()
    {
        $payments = PropertyTaxPayment::with(['bill'])->latest()->paginate(20);
        return view('property-tax.payments.index', compact('payments'));
    }

    public function recordPayment(Request $request, $billId)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,cheque,bank_transfer,online',
            'payment_reference' => 'nullable|string|max:100',
            'payment_date' => 'required|date'
        ]);

        $bill = PropertyTaxBill::findOrFail($billId);

        DB::transaction(function () use ($bill, $validated) {
            PropertyTaxPayment::create([
                'bill_id' => $bill->id,
                'payment_reference' => 'PTP-' . date('Y') . '-' . str_pad(PropertyTaxPayment::count() + 1, 6, '0', STR_PAD_LEFT),
                'amount_paid' => $validated['amount_paid'],
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['payment_reference'],
                'payment_date' => $validated['payment_date'],
                'status' => 'completed',
                'processed_by' => auth()->id()
            ]);

            $totalPaid = PropertyTaxPayment::where('bill_id', $bill->id)->sum('amount_paid');

            if ($totalPaid >= $bill->total_amount) {
                $bill->update(['status' => 'paid']);
            } else {
                $bill->update(['status' => 'partial']);
            }
        });

        return redirect()->route('property-tax.payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function valuations()
    {
        $valuations = PropertyValuation::latest()->paginate(20);
        return view('property-tax.valuations.index', compact('valuations'));
    }

    public function createValuation()
    {
        return view('property-tax.valuations.create');
    }

    public function storeValuation(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|string|max:50',
            'valuation_date' => 'required|date',
            'land_value' => 'required|numeric|min:0',
            'building_value' => 'required|numeric|min:0',
            'total_value' => 'required|numeric|min:0',
            'valuation_method' => 'required|string|in:market,replacement,income',
            'valuer_name' => 'required|string|max:100',
            'valuer_license' => 'nullable|string|max:50',
            'remarks' => 'nullable|string'
        ]);

        $validated['created_by'] = auth()->id();

        PropertyValuation::create($validated);

        return redirect()->route('property-tax.valuations.index')
            ->with('success', 'Property valuation recorded successfully.');
    }

    public function reports()
    {
        $yearlyRevenue = PropertyTaxPayment::selectRaw('YEAR(payment_date) as year, SUM(amount_paid) as total')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->limit(5)
            ->get();

        $categoryStats = PropertyTaxAssessment::join('property_tax_categories', 'property_tax_assessments.category_id', '=', 'property_tax_categories.id')
            ->selectRaw('property_tax_categories.name, COUNT(*) as count, SUM(annual_tax) as total_tax')
            ->groupBy('property_tax_categories.id', 'property_tax_categories.name')
            ->get();

        return view('property-tax.reports.index', compact('yearlyRevenue', 'categoryStats'));
    }
}