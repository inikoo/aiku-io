<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 25 Aug 2021 04:33:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Assets\Country;
use Illuminate\Support\Arr;

class CountrySelectOptionsController extends Controller
{
    public function __invoke(): array
    {
        $selectOptions = [];
        foreach (Country::all() as $country) {
            $selectOptions[$country->id] = $country->name.' ('.$country->code.')';
        }

        return $selectOptions;
    }

    public function getCountriesAddressData(): array
    {
        $selectOptions = [];
        foreach (Country::all() as $country) {
            $selectOptions[$country->id] =
                [
                    'label'               => $country->name.' ('.$country->code.')',
                    'fields'              => Arr::get($country->data, 'fields'),
                    'administrativeAreas' => Arr::get($country->data, 'administrative_areas'),


                ];
        }

        return $selectOptions;
    }
}
