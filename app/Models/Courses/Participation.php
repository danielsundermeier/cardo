<?php

namespace App\Models\Courses;

use App\Traits\HasPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participation extends Model
{
    use HasPath;

    protected $appends = [
        'is_deletable',
        'created_at_formatted',
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

    public function getEditPathAttribute()
    {
        return '';
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
    }

    public function getPathParameterAttribute() : array
    {
        if (is_null($this->date_id)) {
            return [
                'client' => $this->participant->partner_id,
                'participation' => $this->id,
            ];
        }

        return [
            'date' => $this->course_date_id,
            'participation' => $this->id,
        ];
    }

    protected function getBaseRouteAttribute() : string
    {
        if (is_null($this->date_id)) {
            return 'clients.corrections';
        }

        return 'date.participation';
    }

    public function getCreatedAtFormattedAttribute() : string
    {
        return $this->created_at->format('d.m.Y H:i');
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
