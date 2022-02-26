<?php

namespace App\Models\WorkingTimes;

use App\Models\Courses\Course;
use App\Models\Partners\Partner;
use App\Traits\HasPath;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

class WorkingTime extends Model
{
    use HasPath;

    const MONTHS = [
        1 => 'Januar',
        2 => 'Februar',
        3 => 'März',
        4 => 'April',
        5 => 'Mai',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'August',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Dezember',
    ];

    protected $appends = [
        'break_industry_hours_formatted',
        'date_formatted',
        'date_key',
        'effective_industry_hours',
        'effective_industry_hours_formatted',
        'running_industry_hours',
        'running_industry_hours_formatted',
        'end_at_formatted',
        'industry_hours',
        'industry_hours_formatted',
        'is_deletable',
        'is_editable',
        'start_at_formatted',
        'start_at_date_formatted',
        'start_at_with_time_formatted',
    ];

    protected $dates = [
        'end_at',
        'start_at',
    ];

    protected $fillable = [
        'break_industry_hours_formatted',
        'duration_break_in_seconds',
        'duration_in_seconds',
        'end_at',
        'end_at_formatted',
        'industry_hours',
        'industry_hours_formatted',
        'staff_id',
        'start_at',
        'start_at_formatted',
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
            $model->setDurationInSeconds();

            return true;
        });

        static::updating(function($model)
        {
            $model->setDurationInSeconds();

            return true;
        });
    }

    public static function years() : array
    {
        $start_year = 2020;
        $current_year = date('Y');
        $years = [];
        for ($i = $start_year; $i <= $current_year ; $i++) {
            $years[] = $i;
        }

        return $years;
    }

    public static function toIndustryHours(int $seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        return round($hours + ($mins/60) + ($secs / 3600), 2);
    }

    public static function industryHoursToSeconds(float $industry_hours)
    {
        return $industry_hours * 3600;
    }

    public function isDeletable()
    {
        return is_null($this->course_date_id);
    }

    public function isEditable()
    {
        return is_null($this->course_date_id);
    }

    public function setDurationInSeconds()
    {
        if (is_null($this->end_at)) {
            $this->attributes['duration_in_seconds'] = 0;
            return;
        }

        $this->attributes['duration_in_seconds'] = $this->end_at->diffInSeconds($this->start_at);
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
    }

    public function getIsEditableAttribute()
    {
        return $this->isEditable();
    }

    public function getDateKeyAttribute() : string
    {
        return $this->start_at->format('Y-m-d');
    }

    public function getDateFormattedAttribute() : string
    {
        return $this->start_at->format('Y-m-d');
    }

    public function getStartAtFormattedAttribute() : string
    {
        return $this->start_at->format('d.m.Y H:i');
    }

    public function getStartAtWithTimeFormattedAttribute() : string
    {
        return $this->start_at_formatted;
    }

    public function getStartAtDateFormattedAttribute() : string
    {
        return $this->start_at->format('d.m.Y');
    }

    public function getEndAtFormattedAttribute() : string
    {
        if (is_null($this->end_at)) {
            return '';
        }

        return $this->end_at->format('d.m.Y H:i');
    }

    public function setStartAtFormattedAttribute(string $value) : void
    {
        $this->attributes['start_at'] = Carbon::createFromFormat('d.m.Y H:i', $value);
        Arr::forget($this->attributes, 'start_at_formatted');
    }

    public function setEndAtFormattedAttribute(string $value) : void
    {
        $this->attributes['end_at'] = Carbon::createFromFormat('d.m.Y H:i', $value);
        Arr::forget($this->attributes, 'end_at_formatted');
    }

    public function setBreakIndustryHoursFormattedAttribute(string $value) : void
    {
        $this->attributes['duration_break_in_seconds'] = self::industryHoursToSeconds(str_replace(',', '.', $value));
        Arr::forget($this->attributes, 'break_industry_hours_formatted');
    }

    public function getIndustryHoursAttribute() : float
    {
        return self::toIndustryHours($this->duration_in_seconds);
    }

    public function getIndustryHoursFormattedAttribute() : string
    {
        return number_format($this->industry_hours, 2, ',', '.');
    }

    public function getEffectiveIndustryHoursAttribute() : float
    {
        return $this->industry_hours - $this->break_industry_hours;
    }

    public function getEffectiveIndustryHoursFormattedAttribute() : string
    {
        return number_format($this->effective_industry_hours, 2, ',', '.');
    }

    public function getBreakIndustryHoursAttribute() : float
    {
        return self::toIndustryHours($this->duration_break_in_seconds);
    }

    public function getBreakIndustryHoursFormattedAttribute() : string
    {
        return number_format($this->break_industry_hours, 2, ',', '.');
    }

    public function getRunningIndustryHoursAttribute() : float
    {
        if (! is_null($this->end_at)) {
            return 0;
        }

        return self::toIndustryHours(now()->diffInSeconds($this->start_at));
    }

    public function getRunningIndustryHoursFormattedAttribute() : string
    {
        return number_format($this->running_industry_hours, 2, ',', '.');
    }

    public function setIndustryHoursFormattedAttribute($value) : void
    {
        $this->attributes['duration_in_seconds'] = (is_null($value) ? 0 : self::industryHoursToSeconds(str_replace(',', '.', $value)));
        Arr::forget($this->attributes, 'industry_hours_formatted');
    }

    public function setIndustryHoursAttribute($value) : void
    {
        $this->attributes['duration_in_seconds'] = self::industryHoursToSeconds(str_replace(',', '.', $value));
        Arr::forget($this->attributes, 'industry_hours');
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'workingtime';
    }

    public function partner() : BelongsTo
    {
        return $this->belongsTo(Partner::class, 'staff_id');
    }

    public function date() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Courses\Date::class, 'course_date_id');
    }

    public function scopePartner(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('working_times.staff_id', $value);
    }

    public function scopeDate(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->whereDate('working_times.start_at', $value);
    }

    public function scopeMonth(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->whereMonth('working_times.start_at', $value);
    }

    public function scopeYear(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->whereYear('working_times.start_at', $value);
    }

}
