<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:29:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateTradeUnit;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateTradeUnits extends MigrateAurora
{

    protected $signature = 'au_migration:trade {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora trade units';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Part Dimension')
            ->update(['aiku_unit_id' => null]);

    }

    protected function count(): int
    {
        return DB::connection('aurora')->table('Part Dimension')->count();
    }

    protected function migrate(Tenant $tenant)
    {

        DB::connection('aurora')
            ->table('Part Dimension')
            ->orderBy('Part SKU')
            ->chunk(100, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {

                    $result = MigrateTradeUnit::run($auroraData);
                    $this->recordAction($tenant, $result);
                }
            });



    }


}
