<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FDMS Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-info {
            margin-bottom: 15px;
        }
        .receipt-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-info td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
        }
        .total-section {
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Print Receipt</button>
        <button onclick="window.close()">Close</button>
    </div>

    <div class="header">
        <h2>FDMS FISCAL RECEIPT</h2>
        <p><strong>Receipt No: {{ $receipt->receipt_number }}</strong></p>
        @if($receipt->fiscal_receipt_number)
        <p>Fiscal Receipt: {{ $receipt->fiscal_receipt_number }}</p>
        @endif
    </div>

    <div class="receipt-info">
        <table>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                <td><strong>Time:</strong></td>
                <td>{{ $receipt->receipt_time->format('H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Customer:</strong></td>
                <td colspan="3">
                    @if($receipt->customer)
                        {{ $receipt->customer->name }}
                    @else
                        Walk-in Customer
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Terminal:</strong></td>
                <td>
                    @if($receipt->posTerminal)
                        {{ $receipt->posTerminal->terminal_name }}
                    @else
                        Not specified
                    @endif
                </td>
                <td><strong>Cashier:</strong></td>
                <td>{{ $receipt->cashier->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong></td>
                <td>{{ ucfirst($receipt->payment_method) }}</td>
                <td><strong>Currency:</strong></td>
                <td>{{ $receipt->currency_code }}</td>
            </tr>
        </table>
    </div>

    @if($receipt->items)
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach(json_decode($receipt->items, true) as $item)
            <tr>
                <td>{{ $item['description'] ?? 'Service' }}</td>
                <td>{{ $item['quantity'] ?? 1 }}</td>
                <td>{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                <td>{{ number_format($item['total'] ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="total-section">
        <table style="width: 100%;">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($receipt->subtotal_amount, 2) }} {{ $receipt->currency_code }}</strong></td>
            </tr>
            <tr>
                <td><strong>Tax:</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($receipt->tax_amount, 2) }} {{ $receipt->currency_code }}</strong></td>
            </tr>
            <tr style="border-top: 2px solid #000;">
                <td><strong>TOTAL:</strong></td>
                <td style="text-align: right; font-size: 16px;"><strong>{{ number_format($receipt->total_amount, 2) }} {{ $receipt->currency_code }}</strong></td>
            </tr>
            <tr>
                <td><strong>Amount Tendered:</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($receipt->amount_tendered, 2) }} {{ $receipt->currency_code }}</strong></td>
            </tr>
            <tr>
                <td><strong>Change:</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($receipt->change_amount, 2) }} {{ $receipt->currency_code }}</strong></td>
            </tr>
        </table>
    </div>

    @if($receipt->qr_code)
    <div class="qr-code">
        <p><strong>QR Code:</strong> {{ $receipt->qr_code }}</p>
    </div>
    @endif

    @if($receipt->verification_code)
    <div style="text-align: center; margin: 15px 0;">
        <p><strong>Verification Code:</strong> {{ $receipt->verification_code }}</p>
    </div>
    @endif

    @if($receipt->fiscal_signature)
    <div style="text-align: center; margin: 15px 0;">
        <p><strong>Fiscal Signature:</strong></p>
        <p style="font-family: monospace; font-size: 10px; word-break: break-all;">{{ $receipt->fiscal_signature }}</p>
    </div>
    @endif

    <div class="footer">
        <p>This receipt is FDMS compliant and legally recognized by Zimbabwe Revenue Authority (ZIMRA)</p>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
