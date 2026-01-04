<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
class ProductController extends Controller
{
    use AuthorizesRequests; 
    public ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $this->authorize('view_all_products');
        $data['products'] = $this->productService->productPaginate();
        return view('products.index', $data);
       
    }   

    public function create()
    {
        $this->authorize('create_products');
        return view('products.create');
    }

    public function store(ProductRequest $request)
    {
        $this->authorize('create_products');
        $data = $request->validated();
        $this->productService->createProduct($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $this->authorize('update_products');
        $data['product'] = $this->productService->getProductById($id);
        return view('products.edit',  $data);

    }

    public function update(ProductRequest $request, $id)
    {
        $this->authorize('update_products');
        $data = $request->validated();
        $this->productService->updateProduct($id, $data);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorize('delete_products');
        $product = $this->productService->deleteProduct($id);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function reportSales() {
        $this->authorize('report_products');
        $data['productsSale'] = $this->productService->productsSale();
        return view('products.report_sales', $data);
    }
}
