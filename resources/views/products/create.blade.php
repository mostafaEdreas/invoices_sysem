@extends('layouts.app')

@section('title')
    <title>Create Product - Small Printing System</title>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 fw-bold">Add New Product</h1>
                    <p class="text-muted small">Register a new item in your printing inventory.</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold small text-uppercase text-muted">Product Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-box"></i></span>
                                <input type="text" 
                                       class="form-control bg-light border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       id="name" 
                                       placeholder="e.g. Business Cards - Glossy"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback ps-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="stock_quantity" class="form-label fw-bold small text-uppercase text-muted">Initial Stock</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-layer-group"></i></span>
                                    <input type="number" 
                                           class="form-control bg-light border-start-0 ps-0 @error('stock_quantity') is-invalid @enderror" 
                                           name="stock_quantity" 
                                           value="{{ old('stock_quantity', 0) }}" 
                                           id="stock_quantity"
                                           placeholder="0"
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
                                           value="{{ old('price') }}" 
                                           id="price"
                                           placeholder="0.00"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback ps-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                <i class="fas fa-check-circle me-2"></i>Save Product
                            </button>
                            <button type="reset" class="btn btn-link btn-sm text-decoration-none text-muted">
                                Reset Form
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="mt-4 p-3 bg-light-subtle rounded border border-primary-subtle d-flex align-items-center">
                <i class="fas fa-info-circle text-primary me-3 fa-lg"></i>
                <small class="text-muted">
                    This product will be immediately available for selection when generating new customer invoices.
                </small>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .form-control:focus {
        background-color: #fff !important;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }
    .input-group-text {
        border-color: #dee2e6;
    }
    .bg-light-subtle {
        background-color: #eef2ff !important;
    }
    .border-primary-subtle {
        border-color: #c7d2fe !important;
    }
</style>
@endsection