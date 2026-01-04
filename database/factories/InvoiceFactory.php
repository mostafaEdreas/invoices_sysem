<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invice_nubmer' => 'INV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'customer_id'   => Customer::factory(),
            'user_id'       => User::factory(),
            'sub_total'     => 0,
            'discount'      => $this->faker->randomFloat(2, 0, 200) ,
            'total'         => 0,
            'invoice_date'  => now(),
        ];
    }
}
