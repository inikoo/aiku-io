<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 28 Aug 2021 00:35:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Helpers;

use App\Models\Health\Patient;

use App\Models\HumanResources\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperContact
 */
class Contact extends Model implements Auditable
{
    use UsesTenantConnection;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['age', 'formatted_address', 'formatted_dob'];

    public function generateTags(): array
    {
        return [
            //$this->patient->id??'xx',

        ];
    }


    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'email',
        'phone',
        'identity_document_type',
        'identity_document_number'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }



    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }



    public function dependants(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class)->withPivot('relation')->withTimestamps();
    }

    /** @noinspection PhpUnused */
    public function getFormattedDobAttribute(): string
    {
        return Carbon::parse($this->date_of_birth)->locale(auth()->user()->locale ?? 'en')->isoFormat('ll');
    }

    /** @noinspection PhpUnused */
    public function getFormattedAddressAttribute(): string
    {
        if ($this->address) {
            return $this->address->formatted_address;
        } else {
            return '';
        }
    }

    /** @noinspection PhpUnused */
    public function getFormattedGenderAttribute(): string
    {
        return match ($this->gender) {
            'male' => __('Male'),
            'female' => __('Female'),
            default => $this->gender,
        };
    }

    /** @noinspection PhpUnused */
    public function getGenderIconAttribute(): array
    {
        return match ($this->gender) {
            'male' => ['far', 'mars'],
            'female' => ['far', 'venus'],
            default => ['far', 'genderless'],
        };
    }

    public function getAgeAttribute($labelType): string
    {
        $labelType = $labelType ?? 'default';
        $labels    = [
            'infant' => [
                'naked'   => '%y, %m',
                'default' => '%yy, %mm',
                'verbose' => '%y years, %m months'
            ],
            'adult'  => [
                'naked'   => '%y',
                'default' => '%y',
                'verbose' => '%y years'
            ]
        ];

        $date = Carbon::parse($this->date_of_birth);
        $now  = Carbon::now();


        if ($date->diffInDays($now) < 1460) {
            return $date->diff($now)->format(Arr::get($labels, "infant.$labelType"));
        } else {
            return $date->diff($now)->format(Arr::get($labels, "adult.$labelType"));
        }
    }

    /** @noinspection PhpUnused */
    public function getAgeInYearsAttribute(): float|int
    {
        return Carbon::parse($this->date_of_birth)->floatDiffInYears();
    }

}
