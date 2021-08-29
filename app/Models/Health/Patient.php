<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 22:14:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Health;

use App\Models\Helpers\Contact;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperPatient
 */
class Patient extends Model
{
    use UsesTenantConnection;
    use HasFactory;

    protected $appends = ['age', 'formatted_id'];

    protected $fillable = [
        'name','date_of_birth','gender'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)->withPivot('relation')->withTimestamps();
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
    public function getFormattedDobAttribute(): string
    {
        return Carbon::parse($this->date_of_birth)->locale(auth()->user()->locale??'en')->isoFormat('ll');
    }
    /** @noinspection PhpUnused */
    public function getFormattedIdAttribute(): string
    {
        return sprintf('%04d', $this->id);
    }
    /** @noinspection PhpUnused */
    public function getFormattedGenderAttribute(): string
    {
        return match ($this->gender) {
            'Male' => __('Male'),
            'Female' => __('Female'),
            default => $this->gender,
        };
    }

    /** @noinspection PhpUnused */
    public function getGenderIconAttribute(): array
    {
        return match ($this->gender) {
            'Male' => ['far', 'mars'],
            'Female' => ['far', 'venus'],
            default => ['far', 'genderless'],
        };
    }
}
