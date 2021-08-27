<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 17:12:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Aiku\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{

    public function run()
    {
        foreach (config('business_types') as $slug => $data) {
            (new BusinessType())->updateOrCreate(
                [
                    'slug' => $slug
                ],
                [
                    'name' => $data['name'],
                ]
            );
        }
    }
}