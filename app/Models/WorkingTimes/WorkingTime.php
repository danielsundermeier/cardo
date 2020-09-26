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
        'is_deletable',
        'is_editable',
        'industry_hours',
        'industry_hours_formatted',
        'start_at_formatted',
        'date_formatted',
        'date_key',
    ];

    protected $dates = [
        'end_at',
        'start_at',
    ];

    protected $fillable = [
        'staff_id',
        'industry_hours',
        'start_at',
        'start_at_formatted',
        'duration_in_seconds',
        'industry_hours_formatted',
    ];

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
        return $this->start_at->format('d.m.Y');
    }

    public function setStartAtFormattedAttribute(string $value) : void
    {
        $this->attributes['start_at'] = Carbon::createFromFormat('d.m.Y', $value);
        Arr::forget($this->attributes, 'start_at_formatted');
    }

    public function getIndustryHoursAttribute() : float
    {
        return self::toIndustryHours($this->duration_in_seconds);
    }

    public function getIndustryHoursFormattedAttribute() : string
    {
        return number_format($this->industry_hours, 2, ',', '.');
    }

    public function setIndustryHoursFormattedAttribute($value) : void
    {
        $this->attributes['duration_in_seconds'] = self::industryHoursToSeconds(str_replace(',', '.', $value));
        Arr::forget($this->attributes, 'industry_hours_formatted');
    }

    public function setIndustryHoursAttribute($value) : void
    {
        $this->attributes['duration_in_seconds'] = self::industryHoursToSeconds($value);
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
