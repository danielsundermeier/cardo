<?php

namespace App\Models\Receipts;

use App\Models\Items\Item;
use App\Models\Receipts\Line;
use App\Traits\HasPath;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Parental\HasChildren;

class Receipt extends Model
{
    use HasChildren, HasPath;

    protected $appends = [
        'pay_path',
        'date_formatted',
    ];

    protected $casts = [
        'date' => 'date',
        'date_due' => 'date',
    ];

    protected $fillable = [
        'address',
        'date',
        'date_due',
        'is_paid',
        'name',
        'net',
        'number',
        'partner_id',
        'subject',
        'text',
        'text_above',
        'text_below',
        'type',
    ];

    protected $tax = [];

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if (! $model->date) {
                $model->date = now()->startOfDay();
            }

            $model->number = self::nextNumber($model->date);
            $model->setName();

            if (! $model->date_due) {
                $model->date_due = now()->startOfDay();
            }

            $model->setTextAbove();
            $model->setTextBelow();

            return true;
        });
    }

    public static function nextNumber(Carbon $date)
    {
        return self::whereYear('date', $date->year)->max('number') + 1;
    }

    public function setName()
    {
        $this->name = 'CG-E/' . $this->date->year . '/' . str_pad($this->number, 4, '0', STR_PAD_LEFT);
    }

    protected function setTextAbove()
    {
        $this->text_above = 'Vielen Dank für Ihr Vertrauen. Wir stellen Ihnen hiermit folgende Leistungen in Rechnung:';
    }

    protected function setTextBelow()
    {
        $this->text_below = 'Zahlungsbedingungen: Zahlung innerhalb von 14 Tagen ab Rechnungseingang ohne Abzüge auf folgendes Konto: Juliette Rolf, IBAN: DE06 4825 0110 0004 8701 43, Sparkasse Lemgo.';
    }

    public function pay(bool $value = true) : self
    {
        $this->update([
            'is_paid' => $value,
        ]);

        return $this;
    }

    public function addLine(Item $item, array $attributes = []) : Line
    {
        return Line::create([
            'receipt_id' => $this->id,
            'item_id' => $item->id,
            'unit_id' => $item->unit_id,
            'partner_id' => ($item->course_id ? $this->partner_id : null),
            'name' => $item->name,
            'description' => $item->description,
            'quantity' => $attributes['quantity'] ?? 1,
            'discount' => 0,
            'tax' => $item->tax ?? 0.19,
            'unit_price' => $item->unit_price ?? 0,
        ]);
    }

    public function calculateTax()
    {
        $this->attributes['gross'] = 0;
        $this->attributes['net'] = 0;
        $this->attributes['tax_value'] = 0;
        foreach ($this->lines as $line) {
            $key = (int)($line->tax * 100);
            if (! array_key_exists($key, $this->tax))
            {
                $this->tax[$key] = [
                    'tax' => $line->tax,
                    'value' => 0,
                    'net' => 0,
                ];

            }
            $amount = $line->net * $line->tax;
            $this->tax[$key]['value'] += $amount;
            $this->tax[$key]['net'] += $line->net;
            $this->attributes['tax_value'] += $amount;
            $this->attributes['gross'] += $line->gross;
            $this->attributes['net'] += $line->net;
        }
    }

    public function cache() {

        $this->load('lines.item');
        foreach ($this->lines as $key => $line) {
            $line->cache();
        }

        $this->calculateTax();

        $this->update();

    }

    public function pdf()
    {
        $this->load([
            'lines.item',
            'lines.unit',
        ]);
        $this->calculateTax();

        return \PDF::loadView('receipt.pdf', [
            'receipt' => $this,
            'show_tax' => config('app.show_tax'),
        ], [], [
            'margin_top' => 120,
            'margin_bottom' => 20,
            'margin_header' => 20,
        ]);
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getPayPathAttribute()
    {
        return ($this->id ? route('receipt.pay.update', [
            'receipt' => $this->id,
        ]) : '');
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'receipt';
    }

    public function getDateFormattedAttribute() : string
    {
        return $this->date->format('d.m.Y');
    }

    public function getTaxAttribute()
    {
        return $this->tax;
    }

    public function partner() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Partners\Partner::class, 'partner_id');
    }

    public function lines() : HasMany
    {
        return $this->hasMany(\App\Models\Receipts\Line::class, 'receipt_id');
    }

    public function scopeIsPaid(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('receipts.is_paid', $value);
    }

    public function scopePartner(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('receipts.partner_id', $value);
    }
}
