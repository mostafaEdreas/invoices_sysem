@extends('layouts.app')

@section('title')
    <title>Daily Income Report - {{ today()->format('M d, Y') }}</title>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Daily Income Report</h1>
            <p class="text-muted mb-0">
                <i class="far fa-calendar-alt me-1 text-primary"></i> 
                Summary for <strong>{{ today()->format('l, d F Y') }}</strong>
            </p>
        </div>
        <div class="d-print-none">
            <button onclick="window.print()" class="btn btn-white border shadow-sm px-4">
                <i class="fas fa-print me-2 text-primary"></i> Generate Physical Log
            </button>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 12px;">
                <div class="card-body position-relative">
                    <div class="position-absolute end-0 top-0 p-3 opacity-10 text-primary">
                        <i class="fas fa-cash-register fa-4x"></i>
                    </div>
                    <small class="text-uppercase text-muted fw-bold">Daily Gross Revenue</small>
                    <h2 class="display-6 fw-bold text-primary mb-0 mt-2">
                        ${{ number_format($invoices->sum('total'), 2) }}
                    </h2>
                    <div class="mt-2 text-success small">
                        <i class="fas fa-check-circle me-1"></i> Finalized settlement
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body">
                    <small class="text-uppercase text-muted fw-bold">Operational Volume</small>
                    <div class="d-flex align-items-center mt-2">
                        <h2 class="display-6 fw-bold mb-0 me-3 text-dark">{{ $invoices->total() }}</h2>
                        <span class="text-muted">Total Invoices</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-body">
                    <small class="text-uppercase opacity-75 fw-bold">Average Order Value</small>
                    <h2 class="display-6 fw-bold mb-0 mt-2">
                        ${{ $invoices->total() > 0 ? number_format($invoices->sum('total') / $invoices->total(), 2) : '0.00' }}
                    </h2>
                    <p class="small opacity-50 mb-0">Revenue per transaction</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">Closing Ledger</h5>
            <span class="badge bg-light text-muted border px-3">Official Record</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-report-table">
                    <thead class="bg-light border-bottom small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 text-muted">Ref. Number</th>
                            <th class="py-3 text-muted">Customer & Staff</th>
                            <th class="py-3 text-muted text-center">Items</th>
                            <th class="py-3 text-muted text-center">Discount</th>
                            <th class="py-3 text-end pe-4 text-muted">Total Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block">INV-{{ $invoice->invoice_number }}</span>
                                <small class="text-muted font-monospace" style="font-size: 11px;">#{{ $invoice->id }}</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-dark">{{ $invoice->customer_name ?? 'Walk-in Customer' }}</span>
                                    <small class="text-muted small">Issued by: {{ $invoice->employee_name }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-soft-primary text-primary px-3 py-2" style="border-radius: 8px;">
                                    {{ number_format($invoice->items_count ?? 0) }} Units
                                </span>
                            </td>
                            <td style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-2 small fw-bold text-{{ $invoice->discount > 0 ? 'danger' : 'muted' }}">
                                        {{ $invoice->discount }}%
                                    </span>
                                    <div class="progress flex-grow-1" style="height: 4px; max-width: 80px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $invoice->discount }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="fw-bold text-dark">${{ number_format($invoice->total, 2) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-light mb-3"></i>
                                <p class="text-muted mb-0">No revenue recorded during this period.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light-subtle border-top border-2">
                        <tr class="align-middle">
                            <td colspan="3" class="ps-4 py-4">
                                <h6 class="mb-0 fw-bold text-uppercase text-muted small">Daily Reconciliation Total</h6>
                            </td>
                            <td class="text-end" colspan="2 pe-4">
                                <span class="text-muted small fw-bold me-2">NET REVENUE:</span>
                                <span class="h4 fw-bold text-primary mb-0 pe-4">${{ number_format($invoices->sum('total'), 2) }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-print-none">
        {{ $invoices->links() }}
    </div>

    <div class="d-none d-print-block mt-5">
        <div class="row">
            <div class="col-4">
                <div class="border-top pt-2 text-center small">Prepared By</div>
            </div>
            <div class="col-4 offset-4">
                <div class="border-top pt-2 text-center small">Manager Signature</div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .bg-light-subtle { background-color: #fdfdfd; }
    
    .custom-report-table tbody tr:hover {
        background-color: #fbfcfe;
    }

    .bg-soft-primary { background-color: #eef2ff; color: #4e73df; }

    @media print {
        .d-print-none { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table thead th { background-color: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; }
        body { background-color: #fff !important; }
        .tfoot { border-top: 2px solid #000 !important; }
    }
</style>
@endsection