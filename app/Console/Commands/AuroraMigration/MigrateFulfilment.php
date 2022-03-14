<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 23:19:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateCustomer;
use App\Actions\Migrations\MigrateDeletedCustomer;
use App\Actions\Migrations\MigrateShop;
use App\Actions\Migrations\MigrateUniqueStock;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateFulfilment extends MigrateAurora
{
    protected $signature = 'au_migration:ff {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate fulfilment';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {




        foreach (DB::connection('aurora')->table('Store Dimension')
            ->where('Store Type', 'Fulfilment')->get() as $auStoreData) {

            DB::connection('aurora')->table('Customer Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Customer Deleted Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
        }


        DB::connection('aurora')->table('Fulfilment Rent Transaction Fact')
            ->update(['aiku_id' => null]);

        DB::connection('aurora')->table('Fulfilment Asset Dimension')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count=0;


        foreach (DB::connection('aurora')->table('Store Dimension')
            ->where('Store Type', 'Fulfilment')->get() as $auStoreData) {
            $count = DB::connection('aurora')->table('Customer Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->count();
            $count += DB::connection('aurora')->table('Customer Deleted Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->count();
        }


        $count += DB::connection('aurora')->table('Fulfilment Asset Dimension')->count();



        return $count;
    }

    protected function migrate(Tenant $tenant)
    {

        foreach (DB::connection('aurora')->table('Store Dimension')
            ->where('Store Type', 'Fulfilment')->get() as $auStoreData) {


            MigrateShop::run($auStoreData);

            DB::connection('aurora')->table('Customer Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->orderBy('Customer Key')->chunk(1000, function ($chunk) use ($tenant) {
                    foreach ($chunk as $auroraData) {
                        $result = MigrateCustomer::run($auroraData);
                        $this->recordAction($tenant, $result);
                    }
                });


            DB::connection('aurora')->table('Customer Deleted Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->orderBy('Customer Key')->chunk(1000, function ($chunk) use ($tenant) {
                    foreach ($chunk as $auroraData) {
                        if (!$auroraData->{'Customer Key'}) {
                            continue;
                        }
                        if ($auroraData->{'Customer Deleted Metadata'} == '') {
                            continue;
                        }

                        $result = MigrateDeletedCustomer::run(
                            $auroraData
                        );
                        $this->recordAction($tenant, $result);
                    }
                });


        }

        foreach (DB::connection('aurora')->table('Fulfilment Asset Dimension')->get() as $auroraData) {
            $result = MigrateUniqueStock::run($auroraData);
            $this->recordAction($tenant, $result);
        }

    }


}
