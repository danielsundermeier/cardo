<?php

namespace App\Models\Courses;

use App\Traits\HasPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    use HasPath;

    protected $appends = [
        // 'edit_path',
        // 'is_deletable',
        // 'path',
    ];

    protected $fillable = [
        'course_date_id',
        'participant_id',
    ];

    protected $table = 'course_participation';

    public function isDeletable()
    {
        return true;
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
    }

    public function getPathParameterAttribute() : array
    {
        return [
            'date' => $this->course_id,
            'participation' => $this->id,
        ];
    }

    protected function baseRoute() : string
    {
        return 'date.participation';
    }

    public function date() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Courses\Date::class, 'course_date_id');
    }

    public function participant() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Courses\Participant::class, 'participant_id');
    }
}
