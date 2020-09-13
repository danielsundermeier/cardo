<?php

namespace App\Models\Receipts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function isDeletable() : bool
    {
        return true;
    }

    public function getUnitPriceFormattedAttribute()
    {
        $min = 2;
        $max = 6;
        $price = $this->attributes['unit_price'] == 0 ? 0 : rtrim($this->attributes['unit_price'], 0);
        $decimals = strlen(substr( $price, (strpos($price, '.') + 1) ) );
        $decimals = (int)($decimals < $min ? $min : ($decimals > $max ? $max : $decimals));

        return number_format($price, $decimals, ',', '');
    }

    /**
     * Gibt die notwendige Anzahl Nachkommastellen zurück, die benötigt wird
     */
    protected function decimals(float $zahl=0, int $min=0, int $max=6): int
    {
        $zahl = rtrim($zahl, 0);
        $nachkommastellen = strlen(substr( $zahl, (strpos($zahl, '.')+1) ) );

        return (int)($nachkommastellen < $min ? $min : ($nachkommastellen > $max ? $max : $nachkommastellen));
    }

    public function setQuantityAttribute($value) {
        $this->attributes['quantity'] = str_replace(',', '.', $value);
    }

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = str_replace(',', '.', $value) / 100;
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = str_replace(',', '.', $value);
        $this->attributes['net'] = $this->attributes['unit_price'] * $this->attributes['quantity'] * (1 - $this->attributes['discount']) * 100;
        $this->attributes['gross'] = $this->attributes['net'] * (1 + $this->attributes['tax']);
    }

    public function item() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Items\Item::class, 'item_id');
    }

    public function unit() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Items\Unit::class, 'unit_id');
    }
}
