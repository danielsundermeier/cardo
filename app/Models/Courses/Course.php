<?php

namespace App\Models\Courses;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

class Course extends Model
{
    const DAYS = [
        'Sonntag',
        'Montag',
        'Dienstag',
        'Mittwoch',
        'Donnerstag',
        'Freitag',
        'Samstag',
    ];

    protected $appends = [
        'edit_path',
        'is_deletable',
        'path',
        'time_formatted',
    ];

    protected $dates = [
        'time',
    ];

    protected $fillable = [
        'day',
        'description',
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
        return ! $this->dates()->exists();
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
        return ($this->id ? route($this->baseRoute() . '.' . $action, ['course' => $this->id]) : '');
    }

    protected function baseRoute() : string
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
        return $this->hasOne(\App\Models\Items\Item::class, 'course_id');
    }

    public function participants() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Participant::class, 'course_id');
    }
}
