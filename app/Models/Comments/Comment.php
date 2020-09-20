<?php

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if (! $model->user_id) {
                $model->user_id = auth()->user()->id;
            }

            return true;
        });
    }

    protected $appends = [
        'created_at_formatted',
    ];

    protected $fillable = [
        'text',
        'user_id',
    ];

    public function getCreatedAtFormattedAttribute() : string
    {
        return $this->created_at->addHours(2)->format('d.m.Y H:i');
    }

    public function commentable() : MorphTo
    {
        return $this->morphTo('commentable');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
