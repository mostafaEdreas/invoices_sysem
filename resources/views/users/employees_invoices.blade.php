@extends('layouts.app')

@section('title')
    <title>User Sales Performance - Admin Report</title>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">User Performance Report</h1>
            <p class="text-muted mb-0">Analysis of revenue contribution and invoice volume per user.</p>
        </div>
        <div class="btn-group d-print-none shadow-sm">
            <button onclick="window.print()" class="btn btn-white border">
                <i class="fas fa-print me-2 text-primary"></i> Export PDF
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-white border">
                <i class="fas fa-users me-2 text-secondary"></i> User List
            </a>
        </div>
    </div>

    @php
        $topPerformer = $report->sortByDesc('total_sales')->first();
        $grandTotalRevenue = $report->sum('total_sales');
    @endphp
    
    @if($topPerformer && $topPerformer->total_sales > 0)
    <div class="row mb-4 d-print-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-1 bg-primary d-flex align-items-center justify-content-center text-white">
                            <i class="fas fa-award fa-3x"></i>
                        </div>
                        <div class="col-md-11 p-4 bg-white">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="fw-bold text-dark mb-1">Leading Contributor</h5>
                                    <p class="text-muted mb-0">
                                        <span class="text-primary fw-bold">{{ $topPerformer->name }}</span> is currently the top performer, 
                                        responsible for <span class="fw-bold text-dark">{{ number_format(($topPerformer->total_sales / $grandTotalRevenue) * 100, 1) }}%</span> of total revenue.
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <h3 class="fw-bold text-primary mb-0">${{ number_format($topPerformer->total_sales, 2) }}</h3>
                                    <small class="text-muted text-uppercase small">Lifetime Sales</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold" style="letter-spacing: 1px;">User Identity</th>
                            <th class="py-3 text-center text-uppercase small fw-bold" style="letter-spacing: 1px;">Invoice Count</th>
                            <th class="py-3 text-uppercase small fw-bold" style="letter-spacing: 1px;">Revenue Share</th>
                            <th class="py-3 text-end pe-4 text-uppercase small fw-bold" style="letter-spacing: 1px;">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $user)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold border">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="text-muted small">UID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                    {{ number_format($user->invoices_count) }} Invoices
                                </span>
                            </td>
                            <td style="width: 25%;">
                                @php 
                                    $percentage = $grandTotalRevenue > 0 ? ($user->total_sales / $grandTotalRevenue) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <small class="text-muted fw-bold">{{ number_format($percentage, 1) }}%</small>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="fw-bold text-dark fs-6">${{ number_format($user->total_sales ?? 0, 2) }}</div>
                                <div class="small text-muted">Avg: ${{ $user->invoices_count > 0 ? number_format($user->total_sales / $user->invoices_count, 2) : '0.00' }} / inv</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                                <p class="text-muted">No sales data found for any user.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light border-top border-2">
                        <tr class="align-middle">
                            <td colspan="2" class="ps-4 py-4">
                                <h6 class="mb-0 fw-bold text-uppercase text-muted small">Global System Totals</h6>
                            </td>
                            <td class="text-end">
                                <span class="text-muted small fw-bold text-uppercase">Total Volume:</span>
                                <div class="fw-bold text-dark">{{ number_format($report->sum('invoices_count')) }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="text-muted small fw-bold text-uppercase">Grand Revenue:</span>
                                <div class="fw-bold text-primary fs-4">${{ number_format($grandTotalRevenue, 2) }}</div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-none d-print-block mt-5 text-center small text-muted border-top pt-3">
        System Generated Performance Audit - Page 1 of 1 - Printed: {{ now()->format('Y-m-d H:i') }}
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .btn-white { background-color: #fff; color: #444; }
    .bg-primary-subtle { background-color: #eef2ff !important; color: #4e73df !important; }
    .avatar-sm { width: 35px; height: 35px; font-size: 0.8rem; }
    
    @media print {
        body { background-color: white !important; }
        .d-print-none { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table thead th { background-color: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; }
        .progress { border: 1px solid #ddd; }
    }
</style>
@endsection