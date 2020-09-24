<?php

namespace App\Models\Tasks;

use App\Traits\HasComments;
use App\Traits\HasPath;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasComments, HasPath;

    const PRIORITIES = [
        0 => 'Hoch',
        1 => 'Mittel',
        2 => 'Niedrig',
    ];

    protected $appends = [
        'is_deletable',
        'complete_path',
    ];

    protected $dates = [
        'is_completed_at'
    ];

    protected $fillable = [
        'category_id',
        'completer_id',
        'creator_id',
        'description',
        'is_completed',
        'is_completed_at',
        'name',
        'note',
        'priority',
        'staff_id',
    ];

    public function complete(User $user)
    {
        $this->update([
            'completer_id' => $user->id,
            'is_completed' => true,
            'is_completed_at' => now()
        ]);
    }

    public function incomplete()
    {
        $this->update([
            'completer_id' => null,
            'is_completed' => false,
            'is_completed_at' => null
        ]);
    }

    public function isDeletable()
    {
        return true;
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
    }

    public function getCompletePathAttribute()
    {
        return $this->path . '/complete';
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'task';
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Tasks\Category::class, 'category_id');
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function completer() : BelongsTo
    {
        return $this->belongsTo(User::class, 'completer_id');
    }

    public function partner() : BelongsTo
    {
        return $this->belongsTo(\App\Models\Partners\Partner::class, 'staff_id');
    }

    public function scopeCategory(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('tasks.category_id', $value);
    }

    public function scopeIsCompleted(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('tasks.is_completed', $value);
    }

    public function scopePriority(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('tasks.priority', $value);
    }

    public function scopeSearch(Builder $query, $value) : Builder
    {
        if (! $value) {
            return $query;
        }

        return $query->where('tasks.name', 'LIKE', '%' . $value . '%');
    }

    public function scopePartner(Builder $query, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('tasks.staff_id', $value);
    }
}
