<?php

namespace App\Models\Partners;

use App\Models\Courses\Course;
use App\Models\Courses\Participant;
use App\Traits\HasComments;
use App\Traits\HasPath;
use App\Traits\IsDeletable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class Partner extends Model
{
    use HasComments, HasPath, IsDeletable;

    const UPDATE_RULES = [

    ];

    protected $appends = [
        'billing_address',
        'birthday_formatted',
        'is_deletable',
        'name',
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
        'is_staff',
        'is_supplier',
        'user_id',
        'job',
        'birthday_at',
        'height_in_cm',
        'medical_conditions',
        'is_active',
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
        return (! $this->participants()->exists() &&
            ! $this->receipts()->exists() &&
            ! $this->courses()->exists() &&
            ! $this->dates()->exists() &&
            ! $this->healthdatas()->exists());
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

    public function getLinkAttribute() : string
    {
        return '<a href="' . $this->path . '">' . $this->name . '</a>';
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

    public function getWorkingTimeUrlAttribute() : string
    {
        return URL::signedRoute('staff.workingtime.show', ['staff' => $this->id]);
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
        return $this->hasMany(\App\Models\Partners\Healthdata::class, 'partner_id');
    }

    public function histories() : HasMany
    {
        return $this->hasMany(\App\Models\Partners\History::class, 'partner_id');
    }

    public function participants() : HasMany
    {
        return $this->hasMany(\App\Models\Courses\Participant::class, 'partner_id');
    }

    public function receipts() : HasMany
    {
        return $this->hasMany(\App\Models\Receipts\Receipt::class, 'partner_id');
    }

    public function workingtimes() : HasMany
    {
        return $this->hasMany(\App\Models\WorkingTimes\WorkingTime::class, 'staff_id');
    }

    public function tasks() : HasMany
    {
        return $this->hasMany(\App\Models\Tasks\Task::class, 'staff_id');
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

    public function scopeOrderByName(Builder $query) : Builder
    {
        return $query->orderBy('firstname', 'ASC')
                ->orderBy('lastname', 'ASC');
    }

    public function scopeIsActive(Builder $query, $value) : Builder {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('is_active', $value);
    }

    public function scopeSearch(Builder $query, $value) : Builder {
        if (is_null($value)) {
            return $query;
        }

        $value = strtolower($value);

        return $query->where( function ($query) use($value) {
            $query
                ->orWhere(DB::raw('LOWER(firstname)'), 'LIKE', '%' . $value . '%')
                ->orWhere(DB::raw('LOWER(lastname)'), 'LIKE', '%' . $value . '%');
        });
    }

    public function scopeUpcomingBirthdays(Builder $query, int $days = 7) : Builder
    {
        return $query->whereRaw('DATE_ADD(birthday_at,
                INTERVAL YEAR(CURDATE())-YEAR(birthday_at)
                         + IF((DAYOFYEAR(CURDATE()) - :leapyear_offset) > DAYOFYEAR(birthday_at),1,0)
                YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ' . $days . ' DAY)', [
                'leapyear_offset' => (now()->isLeapYear() ? 1 : 0)
            ]);
    }
}
