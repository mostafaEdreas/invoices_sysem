<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* PDF engines prefer standard fonts */
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 13px; 
            color: #333; 
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        
        /* Layout Utilities */
        .w-100 { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .text-muted { color: #777; }
        
        /* Header styling */
        .invoice-header {
            margin-bottom: 40px;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            color: #4e73df;
            font-weight: bold;
            margin: 0;
        }

        /* Information Grid using Tables (Most reliable for PDF) */
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }

        /* Products Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th {
            background-color: #4e73df;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 11px;
            padding: 10px;
            text-align: left;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eeeeee;
        }
        .items-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        /* Summary Section */
        .summary-container {
            margin-top: 30px;
        }
        .totals-table {
            width: 35%;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 0;
        }
        .grand-total {
            border-top: 1px solid #4e73df;
            font-size: 16px;
            color: #4e73df;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 30px;
            width: 100%;
            text-align: center;
            font-size: 11px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="invoice-header">
        <table class="w-100">
            <tr>
                <td>
                    <h1 class="company-name">YOUR COMPANY</h1>
                    <p class="text-muted">
                        123 Business Street, Cairo, Egypt<br>
                        Phone: +20 123 456 789
                    </p>
                </td>
                <td class="text-right">
                    <h1 style="margin:0; color: #ccc;">INVOICE</h1>
                    <p style="margin:0;"><strong>No:</strong> #{{ $invoice->invoice_number }}</p>
                    <p style="margin:0;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <span class="text-muted fw-bold" style="font-size: 11px;">BILL TO:</span><br>
                <div style="margin-top: 5px;">
                    <strong style="font-size: 15px;">{{ $invoice->customer_name }}</strong><br>
                    {{ $invoice->customer->address ?? 'No Address Provided' }}
                </div>
            </td>
            <td class="text-right">
                <span class="text-muted fw-bold" style="font-size: 11px;">ISSUED BY:</span><br>
                <div style="margin-top: 5px;">
                    <strong>{{ $invoice->employee_name }}</strong><br>
                    Authorized Representative
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Product Description</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->products as $product)
            <tr>
                <td>
                    <strong>{{ $product->name }}</strong>
                </td>
                <td class="text-center">{{ $product->pivot->quantity }}</td>
                <td class="text-right">${{ number_format($product->pivot->unit_price, 2) }}</td>
                <td class="text-right fw-bold">${{ number_format($product->pivot->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-container">
        <table class="totals-table">
            <tr>
                <td class="text-muted">Subtotal</td>
                <td class="text-right">${{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td class="text-muted">Discount ({{ $invoice->discount }}%)</td>
                <td class="text-right">-${{ number_format(($invoice->sub_total * $invoice->discount / 100), 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>TOTAL</td>
                <td class="text-right">${{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <div style="margin-top: 50px; font-size: 11px;">
        <p class="fw-bold">Terms & Notes:</p>
        <p class="text-muted">Please pay within 30 days of receiving this invoice. Payments can be made via Bank Transfer or Portal.</p>
    </div>

    <div class="footer">
        Thank you for your business! | www.yourcompany.com
    </div>

</body>
</html>