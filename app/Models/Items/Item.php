<?php

namespace App\Models\Items;

use App\Models\Courses\Course;
use App\Models\Items\Unit;
use App\Models\Receipts\Line;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Item extends Model
{
    protected $appends = [
        'edit_path',
        'is_deletable',
        'path',
    ];

    protected $dates = [

    ];

    protected $fillable = [
        'name',
        'course_id',
        'unit_id',
        'unit_price',
        'unit_price_formatted',
        'tax',
        'is_subscription',
        'is_flatrate',
        'revenue_account_number',
        'expense_account_number',
    ];

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
            return true;
        });
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
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
        return ($this->id ? route($this->baseRoute() . '.' . $action, ['item' => $this->id]) : '');
    }

    protected function baseRoute() : string
    {
        return 'item';
    }

    public function getUnitPriceFormattedAttribute() : string
    {
        return number_format($this->attributes['unit_price'], 2, ',', '');
    }

    public function setUnitPriceFormattedAttribute($value)
    {
        $this->attributes['unit_price'] = str_replace(',', '.', $value);
        Arr::forget($this->attributes, 'unit_price_formatted');
    }

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lines() : HasMany
    {
        return $this->hasMany(Line::class, 'item_id');
    }

    public function scopeOrderByName(Builder $query) : Builder
    {
        return $query->orderBy('name', 'ASC');
    }

}
