@extends('layouts.app')

@section('title')
    <title>Product Management - Small Printing System</title>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Products</h1>
            <p class="text-muted small mb-0">Manage your inventory, stock levels, and pricing.</p>
        </div>
        <div class="btn-group shadow-sm">
            @can('report_products')
                <a href="{{ route('products.report.sales') }}" class="btn btn-white border">
                    <i class="fas fa-chart-line me-2 text-secondary"></i>Sales Report
                </a>
            @endcan
            @can('create_products')
                <a href="{{ route('products.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus me-2"></i>Add Product
                </a>
            @endcan
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 border-start border-primary border-4">
                <div class="small text-muted text-uppercase fw-bold">Total Products</div>
                <div class="h4 mb-0 fw-bold">{{ $products->total() }}</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light-subtle border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase" style="width: 100px;">ID</th>
                            <th class="py-3 text-muted small text-uppercase">Product Details</th>
                            <th class="py-3 text-muted small text-uppercase">Stock Status</th>
                            <th class="py-3 text-muted small text-uppercase">Unit Price</th>
                            <th class="py-3 text-muted small text-uppercase text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $item)
                            <tr class="hover-row">
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border font-monospace">#{{ $item->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->name }}</div>
                                    <div class="small text-muted">SKU: {{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td>
                                    @php
                                        $stock = $item->stock_quantity ?? 0;
                                        $badgeClass = $stock > 10 ? 'bg-success-subtle text-success' : ($stock > 0 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger');
                                        $statusText = $stock > 10 ? 'In Stock' : ($stock > 0 ? 'Low Stock' : 'Out of Stock');
                                    @endphp
                                    <div class="d-inline-flex align-items-center px-2.5 py-0.5 rounded-pill small fw-bold {{ $badgeClass }} border" style="padding: 2px 10px; font-size: 0.75rem;">
                                        {{ $stock }} — {{ $statusText }}
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">${{ number_format($item->price, 2) }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        @can('update_products')
                                            <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-primary btn-sm border-0" title="Edit Product">
                                               Edit
                                            </a>
                                        @endcan
                                        @can('delete_products')
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" 
                                                onclick="confirmDelete('delete-form-{{ $item->id }}')" title="Delete Product">
                                               Delete
                                            </button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('products.destroy', $item->id) }}"
                                                method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3 d-block opacity-25"></i>
                                    No products found in the database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($products->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .bg-light-subtle { background-color: #fcfcfc; }
    .btn-white { background-color: #fff; color: #444; }
    .btn-white:hover { background-color: #f8f9fa; }
    
    /* Row hover effect */
    .hover-row:hover { background-color: #fbfcfe; transition: 0.2s; }
    
    /* Custom Badge colors */
    .bg-success-subtle { background-color: #e6fffa; border-color: #b2f5ea !important; color: #2c7a7b !important; }
    .bg-warning-subtle { background-color: #fffaf0; border-color: #feebc8 !important; color: #9c4221 !important; }
    .bg-danger-subtle { background-color: #fff5f5; border-color: #feb2b2 !important; color: #9b2c2c !important; }

    /* Clean Pagination */
    .pagination { margin-bottom: 0; justify-content: flex-end; }
</style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be permanently removed from inventory.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-light px-4 border ms-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>
@endsection