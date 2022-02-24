<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 17:12:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Aiku\AppType;
use Illuminate\Database\Seeder;

class AppTypeSeeder extends Seeder
{

    public function run()
    {
        foreach (config('app_type') as $code => $data) {

            AppType::upsert([
                                     [
                                         'code' => $code,

                                     ],
                                 ],
                            ['code'],
            );

        }

    }
}
