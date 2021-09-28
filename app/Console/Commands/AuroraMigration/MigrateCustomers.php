<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 19:41:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateCustomer;
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
        DB::connection('aurora')->table('Customer Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Customer Deleted Dimension')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Customer Dimension')->count();
        $count += DB::connection('aurora')->table('Customer Deleted Dimension')->count();

        return $count;
    }


    protected function migrate(Tenant $tenant)
    {
        DB::connection('aurora')->table('Customer Dimension')->orderBy('Customer Key')->chunk(1000, function ($chunk) use ($tenant) {
            foreach ($chunk as $auroraData) {
                $result = MigrateCustomer::run($auroraData);
                $this->recordAction($tenant, $result);
            }
        });

        DB::connection('aurora')->table('Customer Deleted Dimension')->orderBy('Customer Key')->chunk(1000, function ($chunk) use ($tenant) {
            foreach ($chunk as $auroraData) {
                if (!$auroraData->{'Customer Key'}) {
                    continue;
                }
                if ($auroraData->{'Customer Deleted Metadata'} == '') {
                    continue;
                }

                $auroraDeletedData = json_decode(gzuncompress($auroraData->{'Customer Deleted Metadata'}));
                $auroraDeletedData->aiku_id=$auroraData->aiku_id;

                $result = MigrateCustomer::run(
                    $auroraDeletedData,
                    deletedData: ['deleted_at' => $auroraData->{'Customer Deleted Date'}]
                );
                $this->recordAction($tenant, $result);
            }
        });
    }
}
