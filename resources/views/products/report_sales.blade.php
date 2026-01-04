@extends('layouts.app')

@section('title')
    <title>Products Sale Report - Small Printing System</title>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold text-dark mb-1">Products Sale Report</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-alt me-1"></i> 
                Report generated on {{ now()->format('M d, Y - H:i') }}
            </p>
        </div>
        <div class="btn-group d-print-none shadow-sm">
            <button onclick="window.print()" class="btn btn-white border">
                <i class="fas fa-print me-2 text-primary"></i> Print Report
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-white border">
                <i class="fas fa-box me-2 text-secondary"></i> Inventory
            </a>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary p-4 text-white">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <div class="px-4 py-3">
                            <small class="text-uppercase text-muted fw-bold small-tracking">Total Revenue</small>
                            <h3 class="mb-0 fw-bold">${{ number_format($productsSale->sum('total_sales_amount'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-success p-4 text-white">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                        <div class="px-4 py-3">
                            <small class="text-uppercase text-muted fw-bold small-tracking">Units Dispatched</small>
                            <h3 class="mb-0 fw-bold">{{ number_format($productsSale->sum('total_quantity')) }} <span class="h6 text-muted fw-normal">Units</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <i class="fas fa-list text-primary me-2"></i>
            <h6 class="mb-0 fw-bold text-dark">Performance by Product</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase">Reference</th>
                            <th class="py-3 text-muted small text-uppercase">Product Description</th>
                            <th class="py-3 text-center text-muted small text-uppercase">Volume Sold</th>
                            <th class="py-3 text-end pe-4 text-muted small text-uppercase">Revenue Contribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productsSale as $item)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border fw-normal">REF-{{ $item->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->name }}</div>
                                    <div class="text-muted small">ID: #{{ $item->id }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-block px-3 py-1 rounded-pill bg-primary-subtle text-primary fw-bold small">
                                        {{ number_format($item->total_quantity ?? 0) }}
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="fw-bold text-dark">
                                        ${{ number_format($item->total_sales_amount ?? 0, 2) }}
                                    </div>
                                    @if($productsSale->sum('total_sales_amount') > 0)
                                        <div class="small text-muted">
                                            {{ number_format(($item->total_sales_amount / $productsSale->sum('total_sales_amount')) * 100, 1) }}% of total
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" height="60" class="opacity-25 mb-3">
                                    <p class="text-muted">No sales activity recorded yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($productsSale->hasPages())
            <div class="card-footer bg-white border-top py-3 d-print-none">
                {{ $productsSale->links() }}
            </div>
        @endif
    </div>

    <div class="d-none d-print-block mt-5 pt-4 border-top text-center text-muted small">
        <p>This is a system-generated document. Printed by: {{ auth()->user()->name ?? 'Administrator' }}</p>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .small-tracking { letter-spacing: 0.05em; font-size: 0.7rem; }
    .bg-primary-subtle { background-color: #eef2ff !important; color: #4e73df !important; }
    .btn-white { background-color: #fff; color: #444; }
    .btn-white:hover { background-color: #f8f9fa; }
    
    .table thead th {
        border-top: none;
        border-bottom-width: 1px;
        font-weight: 700;
    }

    @media print {
        body { background-color: #fff !important; }
        .container { width: 100% !important; max-width: 100% !important; margin: 0; }
        .card { border: none !important; box-shadow: none !important; }
        .card-header { border-bottom: 2px solid #333 !important; }
        .table thead th { background-color: #f8f9fa !important; color: #000 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection