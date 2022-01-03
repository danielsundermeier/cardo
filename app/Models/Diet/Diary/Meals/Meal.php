<?php

namespace App\Models\Diet\Diary\Meals;

use App\Traits\SaveQuietly;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Meal extends Model
{
    use HasLabels,
        HasModelPath,
        SaveQuietly;

    const ROUTE_NAME = 'diet.days.meals';

    protected $appends = [
        'at_formatted',
        'foods_path',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'at',
    ];

    protected $fillable = [
        'user_id',
        'day_id',
        'name',
        'oder_by',
        'at_formatted',
        'rating_points',
        'rating_comment',
    ];

    protected $table = 'diet_days_meals';

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
                'singular' => 'Mahlzeit',
                'plural' => 'Mahlzeiten',
            ],
        ];
    }

    public function cache() : void
    {
        $this->loadMissing([
            'foods',
        ]);

        $this->setNutritionValues()
            ->saveQuietly();
    }

    public function setNutritionValues() : self
    {
        $this->calories = 0;
        $this->carbohydrate = 0;
        $this->fat = 0;
        $this->protein = 0;

        foreach ($this->foods as $food) {
            $this->calories += $food->calories;
            $this->carbohydrate += $food->carbohydrate;
            $this->fat += $food->fat;
            $this->protein += $food->protein;
        }

        return $this;
    }

    public function foods() : HasMany
    {
        return $this->hasMany(\App\Models\Diet\Diary\Meals\Food::class, 'diet_days_meal_id');
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'day' => $this->day_id,
            'meal' => $this->id,
        ];
    }

    public function getFoodsPathAttribute() : string
    {
        return \App\Models\Diet\Diary\Meals\Food::indexPath([
            'diet_days_meal_id' => $this->id,
        ]);
    }

    public function getAtFormattedAttribute() : string
    {
        if (is_null($this->at)) {
            return '';
        }

        return $this->at->format('d.m.Y');
    }

    public function setAtFormattedAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['at'] = null;
        }
        else {
            $this->attributes['at'] = Carbon::createFromFormat('d.m.Y', $value)->setTime(0, 0, 0);
        }
        Arr::forget($this->attributes, 'at_formatted');
    }
}
