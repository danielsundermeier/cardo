<?php

namespace App\Models\Tasks;

use App\Traits\HasComments;
use App\Traits\HasPath;
use App\User;
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
        'edit_path',
        'is_deletable',
        'path',
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
        'user_id',
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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
