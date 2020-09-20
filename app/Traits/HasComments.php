<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    public function comments() : MorphMany
    {
        return $this->morphMany(\App\Models\Comments\Comment::class, 'commentable');
    }
}