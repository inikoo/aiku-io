<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 26 Aug 2021 21:22:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Assets\Language;

class LanguageSelectOptionsController extends Controller
{
    public function __invoke(): array
    {

        $selectOptions=[];
        foreach (Language::all() as $language) {
            $selectOptions[$language->id]=$language->name;
        }
        return $selectOptions;


    }
}
