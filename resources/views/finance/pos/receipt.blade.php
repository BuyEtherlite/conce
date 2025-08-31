<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $collection->receipt_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; }
        .receipt { max-width: 400px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        .divider { border-top: 2px solid #000; margin: 10px 0; }
        .row { display: flex; justify-content: space-between; margin: 5px 0; }
        .total { font-weight: bold; font-size: 16px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        @media print {
            body { padding: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h2>{{ config('app.name', 'Municipal Council') }}</h2>
            <p>Payment Receipt</p>
            <p>{{ $collection->posTerminal->location }}</p>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span>Receipt No:</span>
            <span><strong>{{ $collection->receipt_number }}</strong></span>
        </div>
        
        <div class="row">
            <span>Date & Time:</span>
            <span>{{ $collection->collected_at->format('Y-m-d H:i:s') }}</span>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span>Customer:</span>
            <span>{{ $collection->customerAccount->customer->name }}</span>
        </div>
        
        <div class="row">
            <span>Account No:</span>
            <span>{{ $collection->customerAccount->account_number }}</span>
        </div>
        
        <div class="row">
            <span>Service Type:</span>
            <span>{{ $collection->customerAccount->accountType->name }}</span>
        </div>

        @if($collection->customerAccount->meter_number)
        <div class="row">
            <span>Meter No:</span>
            <span>{{ $collection->customerAccount->meter_number }}</span>
        </div>
        @endif

        <div class="divider"></div>

        <div class="row">
            <span>Previous Balance:</span>
            <span>R{{ number_format($collection->previous_balance, 2) }}</span>
        </div>
        
        <div class="row total">
            <span>Amount Paid:</span>
            <span>R{{ number_format($collection->amount_paid, 2) }}</span>
        </div>
        
        <div class="row">
            <span>New Balance:</span>
            <span>R{{ number_format($collection->new_balance, 2) }}</span>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span>Payment Method:</span>
            <span>{{ $collection->paymentMethod->name }}</span>
        </div>

        @if($collection->reference_number)
        <div class="row">
            <span>Reference:</span>
            <span>{{ $collection->reference_number }}</span>
        </div>
        @endif

        <div class="row">
            <span>Collected By:</span>
            <span>{{ $collection->collector->name }}</span>
        </div>

        <div class="row">
            <span>Terminal:</span>
            <span>{{ $collection->posTerminal->name }}</span>
        </div>

        @if($collection->description)
        <div class="divider"></div>
        <div class="row">
            <span>Description:</span>
        </div>
        <div style="text-align: center; margin: 10px 0;">
            {{ $collection->description }}
        </div>
        @endif

        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>Keep this receipt for your records</p>
            <p style="font-size: 10px;">Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
            <button onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
