<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 17:11:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateStock;
use App\Actions\Migrations\MigrateStockMovement;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateStockMovements extends MigrateAurora
{

    protected $signature = 'au_migration:stock_movements {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora stock movements';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Inventory Transaction Fact')
            ->where('Inventory Transaction Record Type','Movement')
            ->whereNotIn('Inventory Transaction Type',['FailSale','Adjust'])

            ->update(['aiku_id' => null]);

    }

    protected function count(): int
    {
        return DB::connection('aurora')->table('Inventory Transaction Fact')
            ->where('Inventory Transaction Record Type','Movement')
            ->whereNotIn('Inventory Transaction Type',['FailSale','Adjust'])

            ->count();
    }

    protected function migrate(Tenant $tenant)
    {


        DB::connection('aurora')->table('Inventory Transaction Fact')
            ->where('Inventory Transaction Record Type','Movement')
            ->whereNotIn('Inventory Transaction Type',['FailSale','Adjust'])

            ->orderBy('Date')->chunk(1000, function ($chunk) use ($tenant) {
            foreach ($chunk as $auroraData) {
                $result = MigrateStockMovement::run(
                    $auroraData
                );
                $this->recordAction($tenant, $result);
            }
        });

    }


}
