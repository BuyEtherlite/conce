<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Models\Invoice;
use App\Modules\Finance\Models\InvoiceItem;
use App\Modules\Finance\Models\Customer;

class InvoiceController extends Controller
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
        $query = Invoice::with(['customer', 'items']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('invoice_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('invoice_date', '<=', $request->end_date);
        }
        
        // Search by invoice number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $invoices = $query->orderBy('invoice_date', 'desc')
                         ->orderBy('invoice_number', 'desc')
                         ->paginate(25);
        
        return view('finance.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::active()->orderBy('name')->get();
        $invoiceNumber = $this->generateInvoiceNumber();
        
        return view('finance.invoices.create', compact('customers', 'invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'description' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'customer_id' => $request->customer_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'subtotal' => 0,
            'tax_amount' => 0,
            'total_amount' => 0,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        $subtotal = 0;
        foreach ($request->items as $itemData) {
            $amount = $itemData['quantity'] * $itemData['unit_price'];
            $subtotal += $amount;
            
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $itemData['description'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'amount' => $amount,
            ]);
        }

        // Calculate totals
        $taxRate = 0.15; // 15% tax rate - could be configurable
        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount;

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);

        return redirect()->route('finance.invoices.show', $invoice->id)
            ->with('success', 'Invoice created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'items', 'createdBy'])->findOrFail($id);
        
        return view('finance.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // Only allow editing of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('finance.invoices.show', $invoice->id)
                ->with('error', 'Only draft invoices can be edited');
        }
        
        $customers = Customer::active()->orderBy('name')->get();
        
        return view('finance.invoices.edit', compact('invoice', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Only allow editing of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('finance.invoices.show', $invoice->id)
                ->with('error', 'Only draft invoices can be edited');
        }
        
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoice->id,
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'description' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        // Update invoice header
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'customer_id' => $request->customer_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'description' => $request->description,
        ]);

        // Delete existing items and recreate
        $invoice->items()->delete();
        
        $subtotal = 0;
        foreach ($request->items as $itemData) {
            $amount = $itemData['quantity'] * $itemData['unit_price'];
            $subtotal += $amount;
            
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $itemData['description'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'amount' => $amount,
            ]);
        }

        // Recalculate totals
        $taxRate = 0.15; // 15% tax rate
        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount;

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);

        return redirect()->route('finance.invoices.show', $invoice->id)
            ->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Only allow deletion of draft invoices
        if ($invoice->status !== 'draft') {
            return redirect()->route('finance.invoices.index')
                ->with('error', 'Only draft invoices can be deleted');
        }
        
        $invoice->delete();

        return redirect()->route('finance.invoices.index')
            ->with('success', 'Invoice deleted successfully');
    }

    /**
     * Send invoice to customer.
     */
    public function send($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'sent']);
        }
        
        // Email sending logic would go here
        
        return redirect()->route('finance.invoices.show', $invoice->id)
            ->with('success', 'Invoice sent successfully');
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $invoice->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
        
        return redirect()->route('finance.invoices.show', $invoice->id)
            ->with('success', 'Invoice marked as paid');
    }

    /**
     * Generate PDF of invoice.
     */
    public function pdf($id)
    {
        $invoice = Invoice::with(['customer', 'items', 'createdBy'])->findOrFail($id);
        
        // PDF generation logic would go here
        
        return response()->json([
            'success' => true,
            'message' => 'PDF generation functionality to be implemented'
        ]);
    }

    /**
     * Generate unique invoice number.
     */
    private function generateInvoiceNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        
        $lastInvoice = Invoice::where('invoice_number', 'like', "INV-{$year}{$month}%")
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "INV-{$year}{$month}" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}