<?php

namespace App\Models\Tasks;

use App\Traits\HasPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasPath;

    protected $appends = [
        'edit_path',
        'is_deletable',
        'path',
    ];

    protected $dates = [
        //
    ];

    protected $fillable = [
        'name',
    ];

    protected $table = 'task_category';

    public function isDeletable()
    {
        return ! $this->tasks()->exists();
    }

    public function getIsDeletableAttribute()
    {
        return $this->isDeletable();
    }

    protected function getBaseRouteAttribute() : string
    {
        return 'category';
    }

    public function tasks() : HasMany
    {
        return $this->hasMany(\App\Models\Tasks\Task::class, 'category_id');
    }
}
