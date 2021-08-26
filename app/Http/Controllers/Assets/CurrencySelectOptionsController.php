<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 26 Aug 2021 21:06:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Assets\Currency;

class CurrencySelectOptionsController extends Controller
{
    public function __invoke(): array
    {

        $selectOptions=[];
        foreach (Currency::all() as $currency) {
            $selectOptions[$currency->id]=$currency->name.' ('.$currency->code.')';
        }
        return $selectOptions;


    }
}
