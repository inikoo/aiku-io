<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 12 Oct 2021 01:21:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateSupplierHistoricProduct;
use App\Actions\Migrations\MigrateSupplierProduct;
use App\Actions\Migrations\MigrateWorkshopHistoricProduct;
use App\Actions\Migrations\MigrateWorkshopProduct;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateSupplierProducts extends MigrateAurora
{

    protected $signature = 'au_migration:supplier_products {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora supplier products';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }
    protected function reset()
    {
        DB::connection('aurora')->table('Supplier Part Dimension')
            ->update(
                [
                    'aiku_supplier_id' => null,
                    'aiku_workshop_id' => null,

                ]

            );
        DB::connection('aurora')->table('Supplier Part Historic Dimension')
            ->update(
                [
                    'aiku_supplier_historic_product_id' => null,
                    'aiku_workshop_historic_product_id' => null
                ]
            );
        DB::connection('aurora')->table('Image Subject Bridge')->where('Image Subject Object', 'Supplier Part')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Supplier Part Dimension')->count();
        $count += DB::connection('aurora')->table('Supplier Part Historic Dimension')->count();

        return $count;
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Supplier Dimension')
            ->leftJoin('Agent Supplier Bridge','Agent Supplier Supplier Key','Supplier Key')
            ->whereNull('Agent Supplier Agent Key')
            ->get() as $auData) {





            DB::connection('aurora')
                ->table('Supplier Part Dimension')
                ->where('Supplier Part Supplier Key', $auData->{'Supplier Key'})
                ->orderBy('Supplier Part Key')
                ->chunk(100, function ($chunk) use ($tenant, $auData) {
                    foreach ($chunk as $auroraData) {
                        if ($auData->{'Supplier Production'} == 'Yes') {
                            $result = MigrateWorkshopProduct::run($auroraData);
                            $this->recordAction($tenant, $result);

                            foreach (DB::connection('aurora')->table('Supplier Part Historic Dimension')->where('Supplier Part Historic Supplier Part Key', '=', $auroraData->{'Supplier Part Key'})->get() as $auroraHistoricData) {
                                $result = MigrateWorkshopHistoricProduct::run($auroraHistoricData);
                                $this->recordAction($tenant, $result);
                            }
                        } else {
                            $result = MigrateSupplierProduct::run($auroraData);
                            $this->recordAction($tenant, $result);

                            foreach (DB::connection('aurora')->table('Supplier Part Historic Dimension')->where('Supplier Part Historic Supplier Part Key', '=', $auroraData->{'Supplier Part Key'})->get() as $auroraHistoricData) {
                                $result = MigrateSupplierHistoricProduct::run($auroraHistoricData);
                                $this->recordAction($tenant, $result);
                            }
                        }
                    }
                });
        }
    }


}
