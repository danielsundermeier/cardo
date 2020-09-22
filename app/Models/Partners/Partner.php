<?php

namespace App\Models\Partners;

use App\Models\Courses\Course;
use App\Models\Courses\Participant;
use App\Traits\HasComments;
use App\Traits\HasPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Partner extends Model
{
    use HasComments, HasPath;

    const UPDATE_RULES = [

    ];

    protected $appends = [
        'billing_address',
        'birthday_formatted',
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

    protected function getBaseRouteAttribute() : string
    {
        if ($this->is_staff) {
            return 'staff';
        }

        if ($this->is_supplier) {
            return 'supplier';
        }

        return 'client';
    }

    public function getBirthdayFormattedAttribute() : string
    {
        if (is_null(Arr::get($this->attributes, 'birthday_at'))) {
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

    public function courses() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Course::class, 'partner_id');
    }

    public function dates() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Date::class, 'staff_id');
    }

    public function healthdatas() : HasMany
    {
        return $this->hasMany(\App\Partners\Healthdata::class, 'partner_id');
    }

    public function participants() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Participant::class, 'partner_id');
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
