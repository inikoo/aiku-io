<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 19:41:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateCustomer;
use App\Actions\Migrations\MigrateCustomerClient;
use App\Actions\Migrations\MigrateDeletedCustomer;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateCustomers extends MigrateAurora
{
    protected $signature = 'au_migration:crm {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora customers';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        foreach (
            DB::connection('aurora')->table('Store Dimension')
                ->where('Store Type', '!=', 'Fulfilment')->get() as $auStoreData
        ) {
            DB::connection('aurora')->table('Customer Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Customer Deleted Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Customer Client Dimension')
                ->where('Customer Client Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Customer Favourite Product Fact')
                ->where('Customer Favourite Product Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Back in Stock Reminder Fact')
                ->where('Back in Stock Reminder Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Customer Portfolio Fact')
                ->where('Customer Portfolio Store Key', $auStoreData->{'Store Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Attachment Bridge')->where('Subject', 'Customer')
                ->update(['aiku_id' => null]);
        }
    }

    protected function count(): int
    {
        $count = 0;
        foreach (
            DB::connection('aurora')->table('Store Dimension')
                ->where('Store Type', '!=', 'Fulfilment')->get() as $auStoreData
        ) {
            $count += DB::connection('aurora')->table('Customer Dimension')->where('Customer Store Key', $auStoreData->{'Store Key'})->count();
            $count += DB::connection('aurora')->table('Customer Deleted Dimension')->where('Customer Store Key', $auStoreData->{'Store Key'})->count();
            $count += DB::connection('aurora')->table('Customer Client Dimension')->where('Customer Client Store Key', $auStoreData->{'Store Key'})->count();
        }

        return $count;
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (
            DB::connection('aurora')->table('Store Dimension')
                ->where('Store Type', '!=', 'Fulfilment')->get() as $auStoreData
        ) {
            DB::connection('aurora')->table('Customer Dimension')
                ->where('Customer Store Key', $auStoreData->{'Store Key'})
                ->orderBy('Customer Key')->chunk(1000, function ($chunk) use ($tenant) {
                    foreach ($chunk as $auroraData) {
                        $result = MigrateCustomer::run($auroraData);
                        $this->recordAction($tenant, $result);

                        foreach (DB::connection('aurora')->table('Customer Client Dimension')->where('Customer Client Customer Key', '=', $auroraData->{'Customer Key'})->get() as $auroraCustomerClientData) {
                            $result = MigrateCustomerClient::run($auroraCustomerClientData);
                            $this->recordAction($tenant, $result);
                        }
                    }
                });


            DB::connection('aurora')->table('Customer Deleted Dimension')->where('Customer Store Key', $auStoreData->{'Store Key'})
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
    }


}
