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

class CountrySelectOptionsController extends Controller
{
    public function __invoke(): array
    {

        $selectOptions=[];
        foreach (Country::all() as $country) {
            $selectOptions[$country->code]=$country->name;
        }
        return $selectOptions;


    }
}
