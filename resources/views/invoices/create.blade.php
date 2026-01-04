@extends('layouts.app')

@section('title')
    <title>Add Invoice</title>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create New Invoice</h1>
            <p class="text-muted small mb-0">Fill in the details below to generate a new customer invoice.</p>
        </div>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-invoice me-2"></i>General Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Invoice Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">#</span>
                                    <input type="text" name="invoice_number" class="form-control bg-light"
                                        value="{{ old('invoice_number', $nextInvoiceNumber) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Customer</label>
                                <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Invoice Date</label>
                                <input type="date" name="invoice_date" class="form-control"
                                    value="{{ old('invoice_date', date('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-box me-2"></i>Line Items</h6>
                        <button type="button" class="btn btn-sm btn-primary px-3" onclick="addRow()">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle" id="products-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 45%">Product Description</th>
                                        <th style="width: 20%">Quantity</th>
                                        <th style="width: 25%">Line Total</th>
                                        <th class="text-center" style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="product-rows">
                                    <tr class="product-item">
                                        <td class="ps-4">
                                            <select name="products[0][id]" class="form-select product-select border-0 shadow-none"
                                                onchange="calculateRowTotal(this)">
                                                <option value="0" data-price="0">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }}- ${{ number_format($product->price, 2) }} (Q {{ $product->stock_quantity }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="products[0][quantity]" class="form-control quantity-input"
                                                min="1" value="1" oninput="calculateRowTotal(this)">
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark line-total">$0.00</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeRow(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-dark text-white py-3">
                        <h6 class="m-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span id="invoice-subtotal" class="fw-bold">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Discount (%)</span>
                            <input type="number" name="discount" class="form-control form-control-sm w-25 text-end" 
                                value="0" min="0" max="100" oninput="calculateInvoiceTotal()">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h5 mb-0">Grand Total</span>
                            <span id="invoice-total" class="h5 mb-0 text-primary fw-bold">$0.00</span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Generate Invoice
                            </button>
                            <button type="reset" class="btn btn-link btn-sm text-muted">Clear Form</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Styling refinements */
    body { background-color: #f8f9fc; }
    .card { border-radius: 0.5rem; }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .product-item td { padding-top: 1rem; padding-bottom: 1rem; }
    .sticky-top { z-index: 1000; }
</style>
@endsection

@section('scripts')
    <script>
        let rowIdx = 1;

        function addRow() {
            let table = document.getElementById('product-rows');
            let newRow = `
            <tr class="product-item">
                <td class="ps-4">
                    <select name="products[${rowIdx}][id]" class="form-select product-select border-0 shadow-none" onchange="calculateRowTotal(this)">
                        <option value="0" data-price="0">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} ${{ number_format($product->price, 2) }} (Q {{ $product->stock_quantity }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="products[${rowIdx}][quantity]" 
                           class="form-control quantity-input" min="1" value="1" 
                           oninput="calculateRowTotal(this)">
                </td>
                <td>
                    <span class="fw-bold text-dark line-total">$0.00</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;

            table.insertAdjacentHTML('beforeend', newRow);
            rowIdx++;
        }

        function calculateRowTotal(element) {
            const row = element.closest('tr');
            const select = row.querySelector('.product-select');
            const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;

            const total = price * quantity;
            row.querySelector('.line-total').innerText = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

            calculateInvoiceTotal();
        }

        function calculateInvoiceTotal() {
            let subtotal = 0;

            document.querySelectorAll('.product-item').forEach(row => {
                const select = row.querySelector('.product-select');
                const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                subtotal += price * quantity;
            });

            const discountRate = parseFloat(document.querySelector('input[name="discount_rate"]').value) || 0;
            const discountAmount = (subtotal * discountRate) / 100;
            const grandTotal = subtotal - discountAmount;

            document.getElementById('invoice-subtotal').innerText = '$' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('invoice-total').innerText = '$' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function removeRow(btn) {
            const rows = document.querySelectorAll('.product-item');
            if (rows.length > 1) {
                btn.closest('tr').remove();
                calculateInvoiceTotal();
            } else {
                alert("At least one product is required.");
            }
        }
    </script>
@endsection