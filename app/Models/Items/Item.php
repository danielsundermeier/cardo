<?php

namespace App\Models\Items;

use App\Models\Items\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'unit_id',
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

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}