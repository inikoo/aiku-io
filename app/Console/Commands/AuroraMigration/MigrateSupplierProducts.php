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
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Supplier Part Historic Dimension')
            ->update(['aiku_id' => null]);
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
        DB::connection('aurora')
            ->table('Supplier Part Dimension')
            //  ->leftJoin('Part Dimension', 'Supplier Part Part SKU', '=', 'Part SKU')
            ->orderBy('Supplier Part Key')
            ->chunk(100, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {
                    $result = MigrateSupplierProduct::run($auroraData);
                    $this->recordAction($tenant, $result);

                    foreach (DB::connection('aurora')->table('Supplier Part Historic Dimension')->where('Supplier Part Historic Supplier Part Key', '=', $auroraData->{'Supplier Part Key'})->get() as $auroraHistoricData) {
                        $result = MigrateSupplierHistoricProduct::run($auroraHistoricData);
                        $this->recordAction($tenant, $result);
                    }
                }
            });
    }


}
