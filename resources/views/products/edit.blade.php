@extends('layouts.app')

@section('title')
    <title>Edit Product: {{ $product->name }}</title>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 fw-bold">Edit Product</h1>
                    <p class="text-muted small">Update information for <span class="text-primary fw-bold">{{ $product->name }}</span></p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-top: 4px solid #f6c23e !important;">
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold small text-uppercase text-muted">Product Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-tag"></i></span>
                                <input type="text" 
                                       class="form-control bg-light border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}" 
                                       id="name" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="stock_quantity" class="form-label fw-bold small text-uppercase text-muted">Current Stock</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-warehouse"></i></span>
                                    <input type="number" 
                                           class="form-control bg-light border-start-0 ps-0 @error('stock_quantity') is-invalid @enderror" 
                                           name="stock_quantity" 
                                           value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                           id="stock_quantity"
                                           required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback ps-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="price" class="form-label fw-bold small text-uppercase text-muted">Unit Price</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-primary fw-bold">$</span>
                                    <input type="number" 
                                           step="0.01" 
                                           class="form-control bg-light border-start-0 ps-0 @error('price') is-invalid @enderror" 
                                           name="price" 
                                           value="{{ old('price', $product->price) }}" 
                                           id="price"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback ps-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-sm text-dark">
                                <i class="fas fa-save me-2"></i>Update Product Details
                            </button>
                            <p class="text-center small text-muted mt-2">
                                Last modified: {{ $product->updated_at->format('M d, Y - H:i') }}
                            </p>
                        </div>
                    </form>

                </div>
            </div>

            @can('delete_products')
            <div class="mt-5 border-top pt-4">
                <h6 class="text-danger fw-bold small text-uppercase">Danger Zone</h6>
                <div class="card border-danger-subtle bg-danger-subtle mt-2">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                        <span class="small text-danger">Permanently remove this product from the system.</span>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="triggerDelete()">
                            Delete Product
                        </button>
                    </div>
                </div>
                <form id="delete-form-main" action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .form-control:focus {
        background-color: #fff !important;
        border-color: #f6c23e;
        box-shadow: 0 0 0 0.2rem rgba(246, 194, 62, 0.1);
    }
    .bg-danger-subtle { background-color: #fff5f5 !important; border-color: #feb2b2 !important; }
</style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function triggerDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Removing this product may affect historical data in reports.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-light px-4 border ms-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-main').submit();
                }
            })
        }
    </script>
@endsection