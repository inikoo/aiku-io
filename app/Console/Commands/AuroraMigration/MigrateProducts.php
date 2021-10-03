<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 01 Oct 2021 15:19:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateProduct;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateProducts extends MigrateAurora
{

    protected $signature = 'au_migration:products {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora products';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Product Dimension')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        return DB::connection('aurora')->table('Product Dimension')->count();
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Store Dimension')->get() as $auroraStoreData) {
            DB::connection('aurora')->table('Product Dimension')->where('Product Store Key', $auroraStoreData->{'Store Key'})->orderBy('Product ID')->chunk(100, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {
                    $result = MigrateProduct::run($auroraData);
                    $this->recordAction($tenant, $result);
                }
            });
        }
    }


}
