<?php

namespace App\Models\Courses;

use App\Models\Courses\Course;
use App\Models\Courses\Participation;
use App\Models\Partners\Partner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Participant extends Pivot
{
    public $incrementing = true;

    protected $appends = [
        'is_deletable',
    ];

    protected $fillable = [
        'course_id',
        'has_subscription',
        'is_active',
        'open_participations_count',
        'participations_count',
        'partner_id',
    ];

    protected $table = 'course_participant';

    public function cache() : self
    {
        $this->participations_count = $this->participations()->count();
        $this->open_participations_count = 0;

        $this->has_subscription = $this->course->subscription_item->lines()->where('partner_id', $this->partner_id)->whereHas('invoice', function ($query) {
            return $query->where('date', now()->startOfMonth());
        })->exists();

        if ($this->has_subscription) {
            $this->open_participations_count = 99;
            return $this;
        }

        $this->has_subscription = false;
        $this->open_participations_count = $this->course->item->lines()->where('partner_id', $this->partner_id)->sum('quantity') - $this->participations_count;

        return $this;
    }

    public function isDeletable() : bool
    {
        return ($this->participations_count == 0 && $this->open_participations_count == 0);
    }

    public function getIsDeletableAttribute() : bool
    {
        return $this->isDeletable();
    }

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function partner() : BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function participations() : HasMany
    {
        return $this->hasMany(Participation::class, 'participant_id');
    }

    public function scopeIsActive(Builder $query, $value) : Builder {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('is_active', $value);
    }

}
