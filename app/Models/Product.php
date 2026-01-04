<?php

namespace App\Models;

use App\Exceptions\DependencyExistsException;
use App\Traits\HasDependencies;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
        use HasFactory ,HasDependencies;

    protected $fillable = [
        'name',
        'stock_quantity',
        'price'
    ];


    public static function dependentModels(): array
    {
        return [
         'invoices'
        ];
    }

    public static function dependencyExceptionMessage(): string
    {
        return "Cannot delete product because it is associated with existing one or more of (.".implode(', ',static::dependentModels()).").";
    }
     
    public  function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'products_invoices')
            ->withPivot(['quantity', 'unit_price', 'total_price']);
    }


    protected function totalSalesAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? 0,
        );
    }

    protected function totalQuantity(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? 0,
        );
    }
}
