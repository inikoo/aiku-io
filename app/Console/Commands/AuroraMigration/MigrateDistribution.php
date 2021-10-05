<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 01:17:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateLocation;
use App\Actions\Migrations\MigrateWarehouse;
use App\Actions\Migrations\MigrateWarehouseArea;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateDistribution extends MigrateAurora
{

    protected $signature = 'au_migration:dist {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora warehouses, warehouse areas and locations';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Warehouse Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Warehouse Area Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Location Dimension')
            ->update(['aiku_id' => null]);

    }

    protected function count(): int
    {
        $count=  DB::connection('aurora')->table('Warehouse Dimension')->count();
        $count+=  DB::connection('aurora')->table('Warehouse Area Dimension')->count();
        $count+=  DB::connection('aurora')->table('Location Dimension')->count();

        return $count;

    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Warehouse Dimension')->get() as $auroraWarehouseData) {
            $result = MigrateWarehouse::run($auroraWarehouseData);
            $this->recordAction($tenant, $result);

            DB::connection('aurora')->table('Warehouse Area Dimension')->orderBy('Warehouse Area Key')->chunk(100, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraWarehouseAreaData) {
                    $result =   MigrateWarehouseArea::run($auroraWarehouseAreaData);
                    $this->recordAction($tenant, $result);
                }
            });


            DB::connection('aurora')->table('Location Dimension')
                ->orderBy('Location Key')->chunk(100, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {
                    $result =   MigrateLocation::run($auroraData);
                    $this->recordAction($tenant, $result);



                }
            });


        }
    }


}
