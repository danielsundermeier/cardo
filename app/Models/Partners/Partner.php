<?php

namespace App\Models\Partners;

use App\Models\Courses\Course;
use App\Models\Courses\Participant;
use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasComments;

    const UPDATE_RULES = [

    ];

    protected $appends = [
        'billing_address',
        'edit_path',
        'is_deletable',
        'name',
        'path',
    ];

    protected $dates = [
        'birthday_at',
    ];

    protected $fillable = [
        'address',
        'bankname',
        'bic',
        'city',
        'company_name',
        'country',
        'email',
        'faxnumber',
        'firstname',
        'iban',
        'lastname',
        'mobilenumber',
        'number',
        'phonenumber',
        'postcode',
        'website',
        'is_client',
        'ist_staff',
        'is_supplier',
        'user_id',
        'job',
        'birthday_at',
        'height_in_cm',
        'medical_conditions',
    ];

    public function calculateBmi(float $weight_in_kg) : float {
        if ($this->height_in_cm == 0 || $weight_in_kg == 0) {
            return 0;
        }

        $height_in_m = $this->height_in_cm / 100;

        return $weight_in_kg / ($height_in_m * $height_in_m);
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function courses() : BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_participant', 'partner_id', 'course_id')
            ->using(Participant::class)
            ->withPivot([
                'open_participations_count',
                'participations_count',
            ])
            ->withTimestamps()
            ->as('participation');
    }

    public function getIsDeletableAttribute() : bool
    {
        return $this->isDeletable();
    }

    public function getDayFormattedAttribute() : string
    {
        return (is_null($this->day) ? '-' : self::DAYS[$this->day]);
    }

    public function getTimeFormattedAttribute() : string
    {
        return (is_null($this->time) ? '00:00' : $this->time->format('H:i'));
    }

    public function setTimeFormattedAttribute(string $value) : void
    {
        $this->attributes['time'] = Carbon::createFromFormat('H:i', $value);
        Arr::forget($this->attributes, 'time_formatted');
    }

    public function getPathAttribute()
    {
        return $this->path('show');
    }

    public function getEditPathAttribute()
    {
        return $this->path('edit');
    }

    protected function path(string $action = '') : string
    {
        return ($this->id ? route($this->baseRoute() . '.' . $action, ['client' => $this->id]) : '');
    }

    protected function baseRoute() : string
    {
        return 'client';
    }

    public function getBirthdayFormattedAttribute() : string
    {
        if (is_null($this->attributes['birthday_at'])) {
            return '';
        }

        return $this->birthday_at->format('d.m.Y');
    }

    public function getNameAttribute() : string
    {
        if (is_null($this->company_name)) {
            return $this->firstname . ' ' . $this->lastname;
        }

        return $this->company_name;
    }

    public function getBillingAddressAttribute()
    {
        return $this->name . "\n" . $this->address . "\n" .  $this->postcode . ' ' . $this->city . ($this->country ? "\n" . $this->country : '');
    }

    public function healthdatas() : HasMany
    {
        return $this->hasMany(\App\Partners\Healthdata::class, 'partner_id');
    }

    public function scopeStaff(Builder $query, $value = true) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('partners.is_staff', $value);
    }

    public function scopeClient(Builder $query, $value = true) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('partners.is_client', $value);
    }

    public function scopeSupplier(Builder $query, $value = true) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('partners.is_supplier', $value);
    }
}
