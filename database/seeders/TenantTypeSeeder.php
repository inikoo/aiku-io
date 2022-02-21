<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 17:12:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use App\Models\Account\TenantType;
use Illuminate\Database\Seeder;

class TenantTypeSeeder extends Seeder
{

    public function run()
    {
        foreach (config('tenant_type') as $code => $data) {

            TenantType::upsert([
                                     [
                                         'code' => $code,

                                     ],
                                 ],
                               ['code'],
            );

        }

    }
}
