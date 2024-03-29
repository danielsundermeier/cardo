<?php

namespace App\Models\Courses;

use App\Models\Courses\Course;
use App\Models\Partners\Partner;
use App\Traits\HasPath;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

class Date extends Model
{
    use HasPath;

    protected $appends = [
        'at_formatted',
        'is_deletable',
    ];

    protected $dates = [
        'at',
    ];

    protected $fillable = [
        'at',
        'at_formatted',
        'course_id',
        'is_running',
        'staff_id',
    ];

    protected $table = 'course_date';

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
            $start_at = new Carbon($model->at->format('Y-m-d') . ' ' . $model->course->time->format('H:i:s'));
            $end_at = (new Carbon($model->at->format('Y-m-d') . ' ' . $model->course->time->format('H:i:s')))->addSeconds($model->course->duration_in_seconds);

            $model->workingtime()->create([
                'staff_id' => $model->staff_id,
                'start_at' => $start_at,
                'end_at' => $end_at,
            ]);

            return true;
        });

        static::updated(function($model)
        {
            $model->workingtime()->update([
                'staff_id' => $model->staff_id,
                'start_at' => $model->at,
            ]);

            return true;
        });

        static::deleted(function($model)
        {
            $model->workingtime()->delete();

            return true;
        });
    }

    public static function upcomingFor(Partner $partner)
    {
        return self::with([
            'course',
        ])
            ->where('staff_id', $partner->id)
            ->whereRaw('DATE(at) >= CURDATE()')
            ->orderBy('at', 'ASC')
            ->get();
    }

    public function isDeletable()
    {
        return ! $this->participations()->exists();
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
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

    public function getPathParameterAttribute() : array
    {
        return [
            'course' => $this->course_id,
            'date' => $this->id,
        ];
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'course.date';
    }

    public function instructor() : BelongsTo
    {
        return $this->belongsTo(Partner::class, 'staff_id');
    }

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function participations() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Participation::class, 'course_date_id');
    }

    public function workingtime() : HasOne
    {
        return $this->hasOne(\App\Models\WorkingTimes\WorkingTime::class, 'course_date_id');
    }
}
