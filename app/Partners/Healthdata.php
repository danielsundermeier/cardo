<?php

namespace App\Partners;

use Illuminate\Database\Eloquent\Model;

class Healthdata extends Model
{
    protected $appends = [
        'is_deletable',
        'at_formatted',
        'weight_formatted',
    ];

    protected $dates = [
        'at',
    ];

    protected $fillable = [
        'at',
        'weight_in_g',
        'bmi',
        'bloodpresure_systolic',
        'bloodpresure_diastolic',
        'heart_rate',
        'resting_heart_rate',
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
            if (! $model->at) {
                $model->at = now()->startOfDay();
            }

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

    public function getAtFormattedAttribute() : string
    {
        if (is_null($this->attributes['at'])) {
            return '';
        }

        return $this->at->format('d.m.Y');
    }

    public function getWeightFormattedAttribute() : string
    {
        if (empty($this->attributes['weight_in_g'])) {
            return '0,00';
        }

        return number_format($this->weight_in_g / 1000, 2, ',', '.');
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
        return ($this->id ? route($this->baseRoute() . '.' . $action, [
            'partner' => $this->partner_id,
            'healthdata' => $this->id
        ]) : '');
    }

    protected function baseRoute() : string
    {
        return 'partner.healthdatas';
    }
}
