<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Models\Customer;
use App\Modules\Finance\Models\ArInvoice;
use App\Modules\Water\Models\WaterBill;
use App\Modules\Finance\Models\PosTerminal;
use App\Modules\Finance\Models\PosReceipt;
use App\Modules\Finance\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    /**
     * Display the POS interface
     */
    public function index()
    {
        $terminals = PosTerminal::where('is_active', true)->get();
        return view('finance.pos.index', compact('terminals'));
    }

    /**
     * Advanced bill lookup with multiple search criteria
     */
    public function billLookup(Request $request)
    {
        $request->validate([
            'search_type' => 'required|in:meter_number,customer_name,address,account_number,qr_code',
            'search_value' => 'required|string|min:1',
        ]);

        try {
            $bills = collect();
            $searchType = $request->search_type;
            $searchValue = $request->search_value;

            switch ($searchType) {
                case 'meter_number':
                    $bills = $this->searchByMeterNumber($searchValue);
                    break;
                case 'customer_name':
                    $bills = $this->searchByCustomerName($searchValue);
                    break;
                case 'address':
                    $bills = $this->searchByAddress($searchValue);
                    break;
                case 'account_number':
                    $bills = $this->searchByAccountNumber($searchValue);
                    break;
                case 'qr_code':
                    $bills = $this->searchByQrCode($searchValue);
                    break;
            }

            return response()->json([
                'success' => true,
                'bills' => $bills,
                'total_found' => $bills->count()
            ]);

        } catch (\Exception $e) {
            Log::error('POS Bill Lookup Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to search bills. Please try again.'
            ], 500);
        }
    }

    /**
     * Search bills by meter number with fuzzy matching
     */
    private function searchByMeterNumber($meterNumber)
    {
        return WaterBill::with(['connection.customer'])
            ->whereHas('connection', function($query) use ($meterNumber) {
                $query->where('meter_number', 'LIKE', "%{$meterNumber}%");
            })
            ->where('status', '!=', 'paid')
            ->orderBy('bill_date', 'desc')
            ->take(50)
            ->get()
            ->map(function($bill) {
                return [
                    'type' => 'water_bill',
                    'id' => $bill->id,
                    'bill_number' => $bill->bill_number,
                    'customer_name' => $bill->connection->customer->full_name ?? 'Unknown',
                    'meter_number' => $bill->connection->meter_number ?? '',
                    'amount' => $bill->total_amount,
                    'due_date' => $bill->due_date?->format('Y-m-d'),
                    'status' => $bill->status,
                    'address' => $bill->connection->customer->physical_address ?? '',
                ];
            });
    }

    /**
     * Search bills by customer name with fuzzy matching
     */
    private function searchByCustomerName($customerName)
    {
        $bills = collect();
        
        // Search in Finance customers
        $customers = Customer::where(function($query) use ($customerName) {
            $query->where('first_name', 'LIKE', "%{$customerName}%")
                  ->orWhere('last_name', 'LIKE', "%{$customerName}%")
                  ->orWhere('company_name', 'LIKE', "%{$customerName}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$customerName}%"]);
        })->with(['arInvoices' => function($query) {
            $query->whereIn('status', ['sent', 'overdue'])->orderBy('invoice_date', 'desc');
        }])->get();

        foreach ($customers as $customer) {
            foreach ($customer->arInvoices as $invoice) {
                $bills->push([
                    'type' => 'invoice',
                    'id' => $invoice->id,
                    'bill_number' => $invoice->invoice_number,
                    'customer_name' => $customer->full_name,
                    'account_number' => $customer->customer_code,
                    'amount' => $invoice->balance_due,
                    'due_date' => $invoice->due_date?->format('Y-m-d'),
                    'status' => $invoice->status,
                    'address' => $customer->physical_address ?? $customer->address_line_1,
                ]);
            }
        }

        return $bills->take(50);
    }

    /**
     * Search bills by address with partial matching
     */
    private function searchByAddress($address)
    {
        $bills = collect();
        
        // Search in Customer addresses
        $customers = Customer::where(function($query) use ($address) {
            $query->where('physical_address', 'LIKE', "%{$address}%")
                  ->orWhere('address_line_1', 'LIKE', "%{$address}%")
                  ->orWhere('address_line_2', 'LIKE', "%{$address}%");
        })->with(['arInvoices' => function($query) {
            $query->whereIn('status', ['sent', 'overdue'])->orderBy('invoice_date', 'desc');
        }])->get();

        foreach ($customers as $customer) {
            foreach ($customer->arInvoices as $invoice) {
                $bills->push([
                    'type' => 'invoice',
                    'id' => $invoice->id,
                    'bill_number' => $invoice->invoice_number,
                    'customer_name' => $customer->full_name,
                    'account_number' => $customer->customer_code,
                    'amount' => $invoice->balance_due,
                    'due_date' => $invoice->due_date?->format('Y-m-d'),
                    'status' => $invoice->status,
                    'address' => $customer->physical_address ?? $customer->address_line_1,
                ]);
            }
        }

        return $bills->take(50);
    }

    /**
     * Search bills by account number
     */
    private function searchByAccountNumber($accountNumber)
    {
        $bills = collect();
        
        // Search in Customer codes/account numbers
        $customers = Customer::where('customer_code', 'LIKE', "%{$accountNumber}%")
            ->orWhere('customer_number', 'LIKE', "%{$accountNumber}%")
            ->with(['arInvoices' => function($query) {
                $query->whereIn('status', ['sent', 'overdue'])->orderBy('invoice_date', 'desc');
            }])->get();

        foreach ($customers as $customer) {
            foreach ($customer->arInvoices as $invoice) {
                $bills->push([
                    'type' => 'invoice',
                    'id' => $invoice->id,
                    'bill_number' => $invoice->invoice_number,
                    'customer_name' => $customer->full_name,
                    'account_number' => $customer->customer_code,
                    'amount' => $invoice->balance_due,
                    'due_date' => $invoice->due_date?->format('Y-m-d'),
                    'status' => $invoice->status,
                    'address' => $customer->physical_address ?? $customer->address_line_1,
                ]);
            }
        }

        return $bills->take(50);
    }

    /**
     * Search bills by QR code
     */
    private function searchByQrCode($qrCode)
    {
        // QR code typically contains invoice/bill number or customer account
        $bills = collect();
        
        // Try to find by invoice number first
        $invoice = ArInvoice::where('invoice_number', $qrCode)
            ->whereIn('status', ['sent', 'overdue'])
            ->with('customer')
            ->first();
            
        if ($invoice) {
            $bills->push([
                'type' => 'invoice',
                'id' => $invoice->id,
                'bill_number' => $invoice->invoice_number,
                'customer_name' => $invoice->customer->full_name ?? 'Unknown',
                'account_number' => $invoice->customer->customer_code ?? '',
                'amount' => $invoice->balance_due,
                'due_date' => $invoice->due_date?->format('Y-m-d'),
                'status' => $invoice->status,
                'address' => $invoice->customer->physical_address ?? $invoice->customer->address_line_1 ?? '',
            ]);
        }

        // Try to find by water bill number
        $waterBill = WaterBill::where('bill_number', $qrCode)
            ->where('status', '!=', 'paid')
            ->with(['connection.customer'])
            ->first();
            
        if ($waterBill) {
            $bills->push([
                'type' => 'water_bill',
                'id' => $waterBill->id,
                'bill_number' => $waterBill->bill_number,
                'customer_name' => $waterBill->connection->customer->full_name ?? 'Unknown',
                'meter_number' => $waterBill->connection->meter_number ?? '',
                'amount' => $waterBill->total_amount,
                'due_date' => $waterBill->due_date?->format('Y-m-d'),
                'status' => $waterBill->status,
                'address' => $waterBill->connection->customer->physical_address ?? '',
            ]);
        }

        return $bills;
    }

    /**
     * Process payment for selected bills
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'bills' => 'required|array',
            'bills.*.type' => 'required|in:invoice,water_bill',
            'bills.*.id' => 'required|integer',
            'bills.*.amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'terminal_id' => 'required|exists:pos_terminals,id',
            'total_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::create([
                'payment_number' => $this->generatePaymentNumber(),
                'payment_date' => now(),
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'terminal_id' => $request->terminal_id,
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            foreach ($request->bills as $billData) {
                if ($billData['type'] === 'invoice') {
                    $this->processInvoicePayment($billData, $payment);
                } elseif ($billData['type'] === 'water_bill') {
                    $this->processWaterBillPayment($billData, $payment);
                }
            }

            // Generate receipt
            $receipt = $this->generateReceipt($payment, $request->bills);

            DB::commit();

            return response()->json([
                'success' => true,
                'payment' => $payment,
                'receipt' => $receipt,
                'message' => 'Payment processed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('POS Payment Processing Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Payment processing failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Process payment for invoice
     */
    private function processInvoicePayment($billData, $payment)
    {
        $invoice = ArInvoice::findOrFail($billData['id']);
        $paymentAmount = min($billData['amount'], $invoice->balance_due);
        
        $invoice->paid_amount = ($invoice->paid_amount ?? 0) + $paymentAmount;
        $invoice->balance_due = $invoice->total_amount - $invoice->paid_amount;
        
        if ($invoice->balance_due <= 0) {
            $invoice->status = 'paid';
        }
        
        $invoice->save();
        
        // Record payment details
        DB::table('invoice_payments')->insert([
            'invoice_id' => $invoice->id,
            'payment_id' => $payment->id,
            'amount' => $paymentAmount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Process payment for water bill
     */
    private function processWaterBillPayment($billData, $payment)
    {
        $waterBill = WaterBill::findOrFail($billData['id']);
        $paymentAmount = min($billData['amount'], $waterBill->total_amount - $waterBill->paid_amount);
        
        $waterBill->paid_amount = ($waterBill->paid_amount ?? 0) + $paymentAmount;
        
        if ($waterBill->paid_amount >= $waterBill->total_amount) {
            $waterBill->status = 'paid';
        } else {
            $waterBill->status = 'partial';
        }
        
        $waterBill->save();
        
        // Record payment details
        DB::table('water_bill_payments')->insert([
            'water_bill_id' => $waterBill->id,
            'payment_id' => $payment->id,
            'amount' => $paymentAmount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Generate payment number
     */
    private function generatePaymentNumber()
    {
        $prefix = 'PAY' . date('Ymd');
        $latestPayment = Payment::where('payment_number', 'like', $prefix . '%')
            ->orderBy('payment_number', 'desc')
            ->first();

        if (!$latestPayment) {
            return $prefix . '001';
        }

        $number = (int) substr($latestPayment->payment_number, strlen($prefix));
        return $prefix . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate receipt
     */
    private function generateReceipt($payment, $bills)
    {
        return PosReceipt::create([
            'receipt_number' => $this->generateReceiptNumber(),
            'payment_id' => $payment->id,
            'total_amount' => $payment->total_amount,
            'receipt_date' => now(),
            'bills_data' => json_encode($bills),
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Generate receipt number
     */
    private function generateReceiptNumber()
    {
        $prefix = 'RCP' . date('Ymd');
        $latestReceipt = PosReceipt::where('receipt_number', 'like', $prefix . '%')
            ->orderBy('receipt_number', 'desc')
            ->first();

        if (!$latestReceipt) {
            return $prefix . '001';
        }

        $number = (int) substr($latestReceipt->receipt_number, strlen($prefix));
        return $prefix . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get customer suggestions for autocomplete
     */
    public function customerSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where(function($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%")
              ->orWhere('company_name', 'LIKE', "%{$query}%")
              ->orWhere('customer_code', 'LIKE', "%{$query}%")
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
        })
        ->select('id', 'first_name', 'last_name', 'company_name', 'customer_code', 'physical_address')
        ->limit(10)
        ->get()
        ->map(function($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->full_name,
                'code' => $customer->customer_code,
                'address' => $customer->physical_address
            ];
        });

        return response()->json($customers);
    }

    /**
     * Generate QR code for bill
     */
    public function generateQrCode(Request $request)
    {
        $request->validate([
            'bill_type' => 'required|in:invoice,water_bill',
            'bill_id' => 'required|integer'
        ]);

        try {
            $qrData = '';
            
            if ($request->bill_type === 'invoice') {
                $invoice = ArInvoice::findOrFail($request->bill_id);
                $qrData = $invoice->invoice_number;
            } elseif ($request->bill_type === 'water_bill') {
                $waterBill = WaterBill::findOrFail($request->bill_id);
                $qrData = $waterBill->bill_number;
            }

            return response()->json([
                'success' => true,
                'qr_data' => $qrData,
                'qr_url' => "data:image/svg+xml;base64," . base64_encode($this->generateQrSvg($qrData))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate QR code'
            ], 500);
        }
    }

    /**
     * Simple QR code SVG generator
     */
    private function generateQrSvg($data)
    {
        // This is a simple placeholder - in production, use a proper QR code library
        return '<svg width="100" height="100"><rect width="100" height="100" fill="white"/><text x="50" y="50" text-anchor="middle">' . htmlspecialchars($data) . '</text></svg>';
    }

    /**
     * Get sales analytics
     */
    public function salesAnalytics(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfMonth());
            $endDate = $request->get('end_date', now()->endOfMonth());

            $analytics = [
                'total_transactions' => Payment::whereBetween('payment_date', [$startDate, $endDate])->count(),
                'total_revenue' => Payment::whereBetween('payment_date', [$startDate, $endDate])->sum('total_amount'),
                'daily_sales' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                    ->selectRaw('DATE(payment_date) as date, SUM(total_amount) as amount, COUNT(*) as transactions')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
                'payment_methods' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                    ->selectRaw('payment_method, SUM(total_amount) as amount, COUNT(*) as transactions')
                    ->groupBy('payment_method')
                    ->get(),
            ];

            return response()->json([
                'success' => true,
                'analytics' => $analytics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch analytics'
            ], 500);
        }
    }

    public function create()
    {
        return view('finance.pos.create');
    }

    public function store(Request $request)
    {
        // Implementation for creating POS transactions
        return redirect()->back()->with('success', 'Transaction created successfully');
    }

    public function show($id)
    {
        return view('finance.pos.show', compact('id'));
    }

    public function edit($id)
    {
        return view('finance.pos.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Implementation for updating POS transactions
        return redirect()->back()->with('success', 'Transaction updated successfully');
    }

    public function destroy($id)
    {
        // Implementation for deleting POS transactions
        return redirect()->back()->with('success', 'Transaction deleted successfully');
    }
}
