<?php

namespace App\Models\Receipts;

use App\Models\Courses\Participant;
use App\Models\Receipts\Invoice;
use App\Models\Receipts\Receipt;
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
        'partner_id',
    ];

    public function cache()
    {
        if (is_null($this->item->course_id)) {
            return;
        }

        $participant = Participant::firstWhere([
            'course_id' => $this->item->course_id,
            'partner_id' => $this->partner_id,
        ]);
        if (is_null($participant)) {
            return;
        }

        $participant->cache($this->item->is_subscription)
            ->save();
    }

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

    public function getDatevTaxCodeAttribute() : int
    {
        switch ($this->tax) {
            case 0.000: return 1; break;
            case 0.050: return 2; break; // Corona
            case 0.070: return 2; break;
            case 0.107: return 50; break;
            case 0.055: return 49; break;
            case 0.160: return 3; break; // Corona
            case 0.190: return 3; break;
        }
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

    public function partner() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Partners\Partner::class, 'partner_id');
    }

    public function unit() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Items\Unit::class, 'unit_id');
    }

    public function receipt() : BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id')->where('type', Invoice::class);
    }
}
