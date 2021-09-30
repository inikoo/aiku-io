<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 00:04:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateProduct;
use App\Actions\Migrations\MigrateShop;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateShops extends MigrateAurora
{

    protected $signature = 'au_migration:shops {--reset} {--all} {--t|tenant=* : Tenant slug} {--only_shops}';
    protected $description = 'Migrate aurora stores resources';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Store Dimension')
            ->update(['aiku_id' => null]);

        DB::connection('aurora')->table('Product Dimension')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Store Dimension')->count();
        if (!$this->option('only_shops')) {
            $count += DB::connection('aurora')->table('Product Dimension')->count();
        }

        return $count;
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Store Dimension')->get() as $auroraStoreData) {
            $result = MigrateShop::run($auroraStoreData);
            $this->recordAction($tenant, $result);
            if (!$this->option('only_shops')) {
                DB::connection('aurora')->table('Product Dimension')->where('Product Store Key', $auroraStoreData->{'Store Key'})->orderBy('Product ID')->chunk(100, function ($chunk) use ($tenant) {
                    foreach ($chunk as $auroraData) {
                        $result = MigrateProduct::run($auroraData);
                        $this->recordAction($tenant, $result);
                    }
                });
            }
        }
    }


}
