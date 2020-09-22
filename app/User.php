<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function partner() : HasOne
    {
        return $this->hasOne(\App\Models\Partners\Partner::class, 'user_id');
    }

    public function tasks() : HasMany
    {
        return $this->hasMany(\App\Models\Tasks\Task::class, 'user_id');
    }

    public function incompleted_tasks() : HasMany
    {
        return $this->hasMany(\App\Models\Tasks\Task::class, 'user_id')
            ->with(['category'])
            ->where('is_completed', false)
            ->orderBy('priority', 'ASC')
            ->orderBy('name', 'ASC');
    }
}
