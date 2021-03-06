<?php

namespace App\Models\Courses;

use App\Models\Courses\Course;
use App\Models\Courses\Participation;
use App\Models\Partners\Partner;
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
        'open_participations_count',
        'participations_count',
        'partner_id',
    ];

    protected $table = 'course_participant';

    public function cache(bool $is_subscription = false) : self
    {
        $this->participations_count = $this->participations()->count();
        if ($is_subscription) {
            $this->open_participations_count = $this->course->subscription_item->lines()->where('partner_id', $this->partner_id)->whereHas('invoice', function ($query) {
                return $query->where('date', now()->startOfMonth());
            })->exists() ? 99 : 0;
        }
        else {
            $this->open_participations_count = $this->course->item->lines()->where('partner_id', $this->partner_id)->sum('quantity') - $this->participations_count;
        }

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

}
