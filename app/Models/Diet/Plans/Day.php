<?php

namespace App\Models\Diet\Plans;

use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    use HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'diet.plans.days';

    protected $appends = [
        'meals_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'partner_id',
        'plan_id',
        'name',
        'order_by',
    ];

    protected $table = 'diet_plans_days';

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
            $model->setDefaultName();

            return true;
        });

        static::created(function($model)
        {
            return true;
        });

        static::updating(function($model)
        {
            return true;
        });
    }

    public function isDeletable() : bool
    {
        return true;
    }


    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Tag',
                'plural' => 'Tage',
            ],
        ];
    }

    public function meals() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Plans\Meals\Meal::class, 'day_id');
    }

    public function getMealsPathAttribute() : string
    {
        return \App\Models\Diet\Plans\Meals\Meal::indexPath([
            'plan_id' => $this->plan_id,
            'day_id' => $this->id,
        ]);
    }

    public function setDefaultName() : void
    {
        $week_count = ceil($this->order_by / 7);
        $day_count = ($this->order_by % 7) ?: 7;

        $this->name = 'Woche ' . $week_count . ', Tag ' . $day_count;
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'plan' => $this->plan_id,
            'day' => $this->id
        ];
    }
}
