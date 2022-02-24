<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 18:41:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace Database\Seeders;

use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        Tenant::checkCurrent()
            ? $this->runTenantSpecificSeeders()
            : $this->runLandlordSpecificSeeders();
    }

    public function runTenantSpecificSeeders()
    {
        $this->call([
                        JobPositionSeeder::class,
                        //  PatientSeeder::class,
                    ]);
    }

    public function runLandlordSpecificSeeders()
    {
        $this->call([
                        AppTypeSeeder::class,
                        CountrySeeder::class,
                        CurrencySeeder::class,
                        TimezoneSeeder::class,
                        LanguageSeeder::class,
                        PermissionSeeder::class
                    ]);
    }


}
