<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Models\PosTerminal;
use App\Modules\Finance\Models\PosReceipt;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terminals = PosTerminal::with(['receipts' => function ($query) {
            $query->latest()->limit(5);
        }])->get();
        
        return view('finance.pos.index', compact('terminals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance.pos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'terminal_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive,maintenance',
        ]);

        PosTerminal::create($request->all());

        return redirect()->route('finance.pos.index')
            ->with('success', 'POS Terminal created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $terminal = PosTerminal::with('receipts')->findOrFail($id);
        
        return view('finance.pos.show', compact('terminal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $terminal = PosTerminal::findOrFail($id);
        
        return view('finance.pos.edit', compact('terminal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'terminal_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive,maintenance',
        ]);

        $terminal = PosTerminal::findOrFail($id);
        $terminal->update($request->all());

        return redirect()->route('finance.pos.index')
            ->with('success', 'POS Terminal updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $terminal = PosTerminal::findOrFail($id);
        $terminal->delete();

        return redirect()->route('finance.pos.index')
            ->with('success', 'POS Terminal deleted successfully');
    }

    /**
     * Process a payment through POS.
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'terminal_id' => 'required|exists:pos_terminals,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
        ]);

        // Payment processing logic would go here
        
        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully'
        ]);
    }

    /**
     * Lookup bill information.
     */
    public function billLookup(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string',
        ]);

        // Bill lookup logic would go here
        
        return response()->json([
            'success' => true,
            'bill_info' => []
        ]);
    }

    /**
     * Get customer suggestions for POS.
     */
    public function customerSuggestions(Request $request)
    {
        $query = $request->get('query');
        
        // Customer search logic would go here
        
        return response()->json([
            'suggestions' => []
        ]);
    }

    /**
     * Generate QR code for payment.
     */
    public function generateQrCode(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'account_number' => 'required|string',
        ]);

        // QR code generation logic would go here
        
        return response()->json([
            'success' => true,
            'qr_code' => 'base64_encoded_qr_code_here'
        ]);
    }

    /**
     * Get sales analytics.
     */
    public function salesAnalytics(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        // Sales analytics logic would go here
        
        return response()->json([
            'analytics' => [
                'total_sales' => 0,
                'transaction_count' => 0,
                'average_transaction' => 0,
            ]
        ]);
    }
}