<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $appends = [
        'unit_price_formatted',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'quantity' => 'float',
        'discount' => 'float',
    ];

    protected $fillable = [
        'description',
        'discount',
        'gross',
        'item_id',
        'name',
        'net',
        'quantity',
        'receipt_id',
        'tax',
        'unit_id',
        'unit_price',
    ];

    public function getUnitPriceFormattedAttribute()
    {
        $min = 2;
        $max = 6;
        $price = $this->attributes['unit_price'] == 0 ? 0 : rtrim($this->attributes['unit_price'], 0);
        $decimals = strlen(substr( $price, (strpos($price, '.') + 1) ) );
        $decimals = (int)($decimals < $min ? $min : ($decimals > $max ? $max : $decimals));

        return number_format($price, $decimals, ',', '');
    }
}
