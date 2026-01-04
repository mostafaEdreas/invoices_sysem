<?php

namespace App\Services;

use App\Exceptions\DependencyExistsException;
use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Request;

class ProductService
{
    public function productPaginate()  {
        $paginate_number = config('app.paginate_number', 10);

       return  Product::select('id','name','price' ,'stock_quantity')->latest()->paginate( $paginate_number);
    }

    public function createProduct(array $data) : Product {
        return Product::create($data);
    }

    public function getProductById(int $id,string|array $relations = []) : Product {
        return Product::with($relations)->findOrFail($id);
    }

    public function updateProduct(int $id, array $data) : bool {
        $product = $this->getProductById($id);
        return $product->update($data);
    }

    public function deleteProduct($id) : bool {
        $product =  $this->getProductById($id , Product::dependentModels());
        $product->validateNoDependencies();
        return $product->delete();
    }

    public function productsSale() {
           $paginate_number = config('app.paginate_number', 10);

       return  $report = Product::select(['id','name','stock_quantity'])->withSum('invoices as total_quantity', 'products_invoices.quantity')
            ->withSum('invoices as total_sales_amount', 'products_invoices.total_price')    
            ->orderByDesc('total_sales_amount')
            ->paginate($paginate_number);
    }



}