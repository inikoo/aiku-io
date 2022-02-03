<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 28 Aug 2021 00:35:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Traits;


use Carbon\Carbon;

use Illuminate\Support\Arr;


trait HasPersonalData
{



    public function getFormattedDobAttribute(): string
    {
        return Carbon::parse($this->date_of_birth)->locale(auth()->user()->locale ?? 'en')->isoFormat('ll');
    }


    public function getFormattedGenderAttribute(): string
    {
        return match ($this->gender) {
            'male' => __('Male'),
            'female' => __('Female'),
            default => $this->gender,
        };
    }

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

    public function getAgeInYearsAttribute(): float|int
    {
        return Carbon::parse($this->date_of_birth)->floatDiffInYears();
    }

}
