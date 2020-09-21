<?php

namespace App\Models\Courses;

use App\Models\Courses\Course;
use App\Models\Partners\Partner;
use App\Traits\HasPath;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Date extends Model
{
    use HasPath;

    protected $appends = [
        'at_formatted',
        'edit_path',
        'is_deletable',
        'path',
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
}
