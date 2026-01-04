<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductInvoice>
 */
class ProductInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'product_id' => Product::factory(),
            'invoice_id' => Invoice::factory(),
            'quantity'   => $this->faker->numberBetween(1, 10),
            'price'      => 0,
        ];
    }
}
