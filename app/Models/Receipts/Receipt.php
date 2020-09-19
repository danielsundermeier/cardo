<?php

namespace App\Models\Receipts;

use App\Models\Items\Item;
use App\Models\Receipts\Line;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Parental\HasChildren;

class Receipt extends Model
{
    use HasChildren;

    protected $appends = [
        'path',
        'date_formatted',
    ];

    protected $casts = [
        'date' => 'date',
        'date_due' => 'date',
    ];

    protected $fillable = [
        'address',
        'partner_id',
        'date',
        'date_due',
        'name',
        'net',
        'number',
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
        $this->name = 'R' . str_pad($this->number, 3, '0', STR_PAD_LEFT) . '-' . $this->date->year;
    }

    protected function setTextAbove()
    {
        $this->text_above = 'Vielen Dank für Ihr Vertrauen. Wir stellen Ihnen hiermit folgende Leistungen in Rechnung:';
    }

    protected function setTextBelow()
    {
        $this->text_below = 'Zahlungsbedingungen: Zahlung innerhalb von 14 Tagen ab Rechnungseingang ohne Abzüge auf folgendes Konto: Juliette Rolf, IBAN: DE06 4825 0110 0004 8701 43, Sparkasse Lemgo.';
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
            'tax' => $item->tax ?? 0,
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

    public function getPathAttribute()
    {
        return $this->path('show');
    }

    public function getEditPathAttribute()
    {
        return $this->path('edit');
    }

    protected function path(string $action = '') : string
    {
        return ($this->id ? route($this->baseRoute() . '.' . $action, [$this->baseRoute() => $this->id]) : '');
    }

    protected function baseRoute() : string
    {
        return '';
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
}
