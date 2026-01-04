<?php
namespace App\Traits;

use App\Exceptions\QuantityException;
use App\Models\Product;

trait InvoiceHandler
{

    public function prepareInvoiceData(array $products, float $discountPercent): array
    {
        $subTotal = 0;
        $syncData = [];

        $productIds = collect($products)->pluck('id');
    
        $dbProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($products as $item) {
  
            $product = $dbProducts[$item['id']]; // Direct access, no check needed
            if($product->stock_quantity < $item['quantity']) {
                throw new QuantityException("Insufficient stock for product: {$product->name}" , 400);
            }
            $total = $product->price * $item['quantity'];

            $syncData[$item['id']] = [
                'quantity'    => $item['quantity'],
                'unit_price'  => $product->price,
                'total_price' =>$total,
            ];

            $subTotal += $total;
            $product->decrement('stock_quantity', $item['quantity']);
        }

        $discountAmount = $this->calculateDiscount($subTotal, $discountPercent);

        return [
            'sub_total' => $subTotal,
            'discount'  => $discountAmount,
            'total'     => $subTotal - $discountAmount,
            'products'  => $syncData,
        ];
    }
    private function calculateDiscount(float $subTotal, float $discount): float
    {
        return $subTotal * ($discount / 100);

    }
}
