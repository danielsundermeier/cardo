<?php

namespace App\Models\Courses;

use App\Traits\HasPath;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use HasPath;

    const DAYS = [
        'Sonntag',
        'Montag',
        'Dienstag',
        'Mittwoch',
        'Donnerstag',
        'Freitag',
        'Samstag',
    ];

    const DURATIONS = [
        0 => 'Nicht gewählt',
        1800 => '30 Minuten',
        2700 => '45 Minuten',
        3600 => '60 Minuten',
    ];

    protected $appends = [
        'day_formatted',
        'is_deletable',
        'time_formatted',
    ];

    protected $dates = [
        'time',
    ];

    protected $fillable = [
        'day',
        'description',
        'duration_in_seconds',
        'name',
        'time',
        'partner_id',
        'time_formatted',
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
            if (! $model->partner_id) {
                $model->partner_id = auth()->user()->partner->id;
            }

            $model->time = today()->setTime(0, 0, 0);

            return true;
        });
    }

    public function isDeletable() : bool
    {
        return ! $this->dates()->exists() && ! $this->item()->exists() && ! $this->subscription_item()->exists();
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
    }

    public function getDayFormattedAttribute() : string
    {
        return (is_null($this->day) ? '-' : self::DAYS[$this->day]);
    }

    public function getDurationFormattedAttribute() : string
    {
        return Arr::get(self::DURATIONS, $this->attributes['duration_in_seconds'], '-');
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

    public function getLinkAttribute() : string
    {
        return '<a href="' . $this->path . '">' . $this->name . '</a>';
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'course';
    }

    public function dates() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Date::class, 'course_id');
    }

    public function instructor() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Partners\Partner::class, 'partner_id');
    }

    public function item() : HasOne
    {
        return $this->hasOne(\App\Models\Items\Item::class, 'course_id')->where('is_subscription', false);
    }

    public function subscription_item() : HasOne
    {
        return $this->hasOne(\App\Models\Items\Item::class, 'course_id')->where('is_subscription', true);
    }

    public function participants() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Participant::class, 'course_id');
    }

    public function scopeOrderByDay(Builder $query) : Builder
    {
        return $query->orderBy(DB::raw('IF(day = 0, 7, day)'), 'ASC');
    }

    public function scopeOrderByTime(Builder $query) : Builder
    {
        return $query->orderBy(DB::raw('TIME(time)'), 'ASC');
    }
}
