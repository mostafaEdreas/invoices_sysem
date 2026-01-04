@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <a href="{{ route('invoices.index') }}" class="btn btn-link text-decoration-none text-muted p-0">
            <i class="fas fa-chevron-left me-1"></i> Back to Invoice List
        </a>
        <div class="btn-group shadow-sm">
            @can('print_invoices')
                <button onclick="window.print()" class="btn btn-white border">
                    <i class="fas fa-print me-2 text-primary"></i> Print
                </button>
                <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-white border">
                    <i class="fas fa-file-pdf me-2 text-danger"></i> PDF
                </a>
            @endcan
        </div>
    </div>

    <div class="card border-0 shadow-lg invoice-card">
        <div class="card-body p-0">
            <div class="bg-primary pt-2"></div>
            
            <div class="p-5">
                <div class="row mb-5">
                    <div class="col-sm-7">
                        <div class="d-flex align-items-center mb-3">
                            {{-- Optional: <img src="/logo.png" height="40" class="me-3"> --}}
                            <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -1px;">YOUR COMPANY</h2>
                        </div>
                        <div class="text-muted small lh-lg">
                            123 Business Street, Cairo, Egypt<br>
                            <i class="fas fa-envelope me-1"></i> support@company.com<br>
                            <i class="fas fa-phone me-1"></i> +20 123 456 789
                        </div>
                    </div>
                    <div class="col-sm-5 text-sm-end">
                        <h1 class="text-uppercase fw-light text-muted mb-1" style="letter-spacing: 4px;">Invoice</h1>
                        <div class="h5 fw-bold mb-1">#{{ $invoice->invoice_number }}</div>
                        <p class="text-muted small">Issued: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="row mb-5 py-4 border-top border-bottom bg-light-subtle">
                    <div class="col-sm-6 border-start border-primary border-4 ps-4">
                        <div class="text-uppercase small text-muted fw-bold mb-2">Bill To</div>
                        <h5 class="fw-bold mb-1">{{ $invoice->customer_name }}</h5>
                        <div class="text-muted small">{{ $invoice->customer->address ?? 'No Address Provided' }}</div>
                    </div>
                    <div class="col-sm-6 text-sm-end pe-4">
                        <div class="text-uppercase small text-muted fw-bold mb-2">Issued By</div>
                        <h5 class="fw-bold mb-1">{{ $invoice->employee_name }}</h5>
                        <div class="text-muted small">Authorized Representative</div>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-borderless">
                        <thead class="border-bottom">
                            <tr class="text-uppercase small text-muted">
                                <th class="py-3 px-0" style="width: 50px">#</th>
                                <th class="py-3">Description</th>
                                <th class="py-3 text-center">Qty</th>
                                <th class="py-3 text-end">Unit Price</th>
                                <th class="py-3 text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody class="border-bottom">
                            @foreach($invoice->products as $index => $product)
                            <tr class="align-middle">
                                <td class="py-3 px-0 text-muted">{{ $index + 1 }}</td>
                                <td class="py-3">
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                    <div class="small text-muted d-none d-sm-block">Product ID: {{ $product->sku ?? $product->id }}</div>
                                </td>
                                <td class="py-3 text-center">{{ $product->pivot->quantity }}</td>
                                <td class="py-3 text-end">${{ number_format($product->pivot->unit_price, 2) }}</td>
                                <td class="py-3 text-end fw-bold">${{ number_format($product->pivot->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-sm-6 order-2 order-sm-1 mt-4 mt-sm-0">
                        <div class="p-3 border rounded bg-light small">
                            <div class="fw-bold mb-2 text-uppercase">Payment Instructions</div>
                            <p class="mb-0 text-muted">Please include the invoice number <strong>#{{ $invoice->invoice_number }}</strong> in your payment reference. Payment is due within 30 days.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 ms-auto order-1 order-sm-2">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span>${{ number_format($invoice->sub_total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-danger">
                            <span class="small">Discount ({{ $invoice->discount }}%):</span>
                            <span>-${{ number_format(($invoice->sub_total * $invoice->discount / 100), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <h4 class="fw-bold mb-0">Total Due:</h4>
                            <h3 class="fw-bold text-primary mb-0">${{ number_format($invoice->total, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-5 text-center border-top">
                    <p class="text-muted small">Thank you for choosing <strong>Your Company Name</strong>. We appreciate your business!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f3f4f7; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    .invoice-card { overflow: hidden; border-radius: 12px; }
    .bg-light-subtle { background-color: #fcfcfc !important; }
    .btn-white { background-color: #fff; color: #444; }
    .btn-white:hover { background-color: #f8f9fa; }
    
    @media print {
        body { background-color: white !important; }
        .container { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .invoice-card { box-shadow: none !important; border: none !important; }
        .d-print-none { display: none !important; }
        .card-body { padding: 0 !important; }
    }
</style>
@endsection