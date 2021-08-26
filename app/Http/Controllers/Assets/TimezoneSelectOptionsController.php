<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 26 Aug 2021 21:23:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Assets\Timezone;

class TimezoneSelectOptionsController extends Controller
{
    public function __invoke(): array
    {

        $selectOptions=[];
        foreach (Timezone::all() as $timezone) {
            $selectOptions[$timezone->id]=$timezone->formatOffset().' '.$timezone->name;
        }
        return $selectOptions;


    }
}
