<?php

namespace App\Models\Partners;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public $appends = [
        'edit_path',
        'is_deletable',
        'name',
        'path',
    ];

    protected $fillable = [
        'firstname',
        'lastname',
        'is_client',
        'ist_staff',
        'is_supplier',
    ];

    public function isDeletable() : bool
    {
        return true;
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
    }

    public function getDayFormattedAttribute() : string
    {
        return (is_null($this->day) ? '-' : self::DAYS[$this->day]);
    }

    public function getTimeFormattedAttribute() : string
    {
        return (is_null($this->time) ? '00:00' : $this->time->format('H:i'));
    }

    public function setTimeFormattedAttribute(string $value) : void
    {
        $this->attributes['time'] = Carbon::createFromFormat('H:i', $value);
        Arr::forget($this->attributes, 'time_formatted');
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
        return ($this->id ? route($this->baseRoute() . '.' . $action, ['client' => $this->id]) : '');
    }

    protected function baseRoute() : string
    {
        return 'client';
    }

    public function getNameAttribute() : string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeStaff(Builder $query, $value = true) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('partners.is_staff', $value);
    }

    public function scopeClient(Builder $query, $value = true) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('partners.is_client', $value);
    }

    public function scopeSupplier(Builder $query, $value = true) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('partners.is_supplier', $value);
    }
}
