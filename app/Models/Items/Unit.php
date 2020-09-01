<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $appends = [
        'edit_path',
        'is_deletable',
        'path',
    ];

    protected $dates = [

    ];

    protected $fillable = [
        'abbreviation',
        'name',
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
        return ($this->id ? route($this->baseRoute() . '.' . $action, ['unit' => $this->id]) : '');
    }

    protected function baseRoute() : string
    {
        return 'unit';
    }

    public function items() : HasMany
    {
        return $this->hasMany('App\Item');
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where( function ($query) use($value) {
            $query
                ->orWhere('name', 'LIKE', '%' . $value . '%')
                ->orWhere('abbreviation', 'LIKE', '%' . $value . '%');
        });
    }
}
