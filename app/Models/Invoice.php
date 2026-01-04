<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
        use HasFactory;

        public static function booted(){
            static::addGlobalScope(new Scopes\EmployeeInvoices());
        }
     protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'sub_total',
        'discount',
        'total',
        'invoice_date',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class ,'customer_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_invoices')
            ->withPivot(['quantity', 'unit_price', 'total_price']);
           
    }

    public function employee()
    {
        return $this->belongsTo(User::class ,'user_id');
    }

    protected function employeeName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->employee ? $this->employee->name : null,
        );
    }

    protected function customerName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->customer ? $this->customer->name : null,
        );
    }
}
