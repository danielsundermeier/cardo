<?php

namespace App\Models\Partners;

use App\Models\Partners\Healthdata;
use App\Traits\HasPath;
use App\Traits\isDeletable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class History extends Model
{
    use HasPath, IsDeletable;

    protected $appends = [
        'at_formatted',
        'filled_data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dates = [
        'at',
    ];

    protected $fillable = [
        'at',
        'at_formatted',
        'healthdata_id',
        'data',
        'partner_id',
    ];

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(function($model)
        {
            $model->healthdata()->associate(Healthdata::create([
                'at' => $model->at,
                'partner_id' => $model->partner_id,
            ]))->save();

            return true;
        });

        static::updated(function($model)
        {
            $model->healthdata()->update([

            ]);

            return true;
        });

        static::deleted(function($model)
        {
            // $model->healthdata()->delete();

            return true;
        });
    }

    public static function defaultData() : array
    {
        return [
            'sport' => [
                'regular' => false,
                'past' => false,
                'types' => [],
            ],
            'goals' => [],
            'complains' => [],
            'goals' => [],
            'illnesses' => [
                'organs' => [
                    'heart' => false,
                    'lung' => false,
                ],
                'illnesses' => [

                ],
            ],
            'drugs' => [],
            'injuries' => [],
        ];
    }

    public function getFilledDataAttribute() : array
    {
        return array_merge(self::defaultData(), $this->data);
    }

    public function getPathParameterAttribute() : array
    {
        return [
            'client' => $this->partner_id,
            'history' => $this->id,
        ];
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'client.history';
    }

    public function getAtFormattedAttribute() : string
    {
        return $this->at->format('d.m.Y');
    }

    public function setAtFormattedAttribute(string $value) : void
    {
        $this->attributes['at'] = Carbon::createFromFormat('d.m.Y', $value);
        Arr::forget($this->attributes, 'at_formatted');
    }

    public function healthdata() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Partners\Healthdata::class, 'healthdata_id');
    }

    public function partner() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Partners\Partner::class, 'partner_id');
    }
}
