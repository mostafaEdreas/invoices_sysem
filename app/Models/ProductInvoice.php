<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInvoice extends Model
{
        use HasFactory;

    protected $table = 'products_invoices';

    protected $fillable = [
        'product_id',
        'invoice_id',
        'quantity',
        'unit_price', // to keep the accurate price at the time of sale
        'total_price', // for performance optimization
    ];

    public $timestamps = false;
}
