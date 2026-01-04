@extends('layouts.app')

@section('title')
    <title>Invoice Management - Finance Portal</title>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Billing & Invoices</h1>
            <p class="text-muted small mb-0">Track customer transactions and manage financial records.</p>
        </div>
        <div class="btn-group shadow-sm">
            @can('create_invoices')
                <a href="{{ route('invoices.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus-circle me-2"></i>Generate Invoice
                </a>
            @endcan
            @can('report_invoices')
                <button class="btn btn-white border dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-file-export me-2 text-secondary"></i>Reports
                </button>
                <ul class="dropdown-menu shadow-sm">
                    <li><a class="dropdown-item" href="{{ route('invoices.report.today') }}">Daily Settlement</a></li>
                    <li><a class="dropdown-item" href="{{ route('invoices.report.monthly') }}">Monthly Performance</a></li>
                </ul>
            @endcan
        </div>
    </div>

    <div class="row g-3 mb-4 d-print-none">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3 h-100" style="border-radius: 12px;">
                <div class="small text-uppercase opacity-75">Total Revenue (30d)</div>
                <h3 class="fw-bold mb-0">${{ number_format($invoices->sum('total'), 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                <div class="small text-uppercase text-muted">Active Invoices</div>
                <h3 class="fw-bold mb-0 text-dark">{{ $invoices->total() }}</h3>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                <div class="small text-uppercase text-muted">System Status</div>
                <div class="h3 mb-0"><span class="badge bg-success-subtle text-success border-0 px-3">Operational</span></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-bold text-dark">Invoice Ledger</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group input-group-sm border rounded">
                        <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 px-0" placeholder="Search invoice...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-invoice-table">
                    <thead class="bg-light-subtle border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase" style="width: 140px;">Reference</th>
                            <th class="py-3 text-muted small text-uppercase">Customer</th>
                            <th class="py-3 text-muted small text-uppercase">Issued By</th>
                            <th class="py-3 text-muted small text-uppercase text-center">Date</th>
                            <th class="py-3 text-muted small text-uppercase text-end">Amount</th>
                            <th class="py-3 text-muted small text-uppercase text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr class="hover-row">
                                <td class="ps-4">
                                    <div class="font-monospace fw-bold text-dark">
                                        INV-{{ str_pad($invoice->invoice_number, 5, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <span class="badge bg-success-subtle text-success small border-0" style="font-size: 10px;">PAID</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-init me-2">{{ strtoupper(substr($invoice->customer_name ?? 'W', 0, 1)) }}</div>
                                        <div class="fw-bold text-dark">{{ $invoice->customer_name ?? 'Walk-in Customer' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary small">
                                        <i class="fas fa-user-tie me-1 opacity-50"></i>{{ $invoice->employee_name ?? 'System' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="small text-dark fw-semibold">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('H:i A') }}</div>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    ${{ number_format($invoice->total, 2) }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-xs">
                                        @can('view_invoices')
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-white btn-sm border" title="View PDF">
                                              View
                                            </a>
                                        @endcan
                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results</span>
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .bg-light-subtle { background-color: #fcfcfc; }
    .btn-white { background-color: #fff; }
    .hover-row:hover { background-color: #fbfcfe; transition: 0.2s; }
    
    /* Avatar Style */
    .avatar-init {
        width: 28px; height: 28px;
        background-color: #eee;
        color: #777;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: bold;
    }

    /* Badge Style */
    .bg-success-subtle { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
    .bg-primary-subtle { background-color: #eef2ff !important; color: #4e73df !important; }

    /* Shadow adjustments */
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.04)!important; }
    
    .table td { border-bottom: 1px solid #f2f4f8; }
</style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Reverse Transaction?',
                text: "Deleting this invoice will void the financial record. Stock is not automatically restored.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete Invoice',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger px-4 mx-2',
                    cancelButton: 'btn btn-light px-4 border mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>
@endsection