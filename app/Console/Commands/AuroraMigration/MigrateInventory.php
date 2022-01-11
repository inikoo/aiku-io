<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 12:14:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateDeletedStock;
use App\Actions\Migrations\MigrateStock;
use App\Actions\Migrations\MigrateUniqueStock;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateInventory extends MigrateAurora
{

    protected $signature = 'au_migration:inventory {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora inventory';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {

        DB::connection('aurora')->table('Part Dimension')
            ->update(['aiku_id' => null]);

        DB::connection('aurora')->table('Part Deleted Dimension')
            ->update(['aiku_id' => null]);

        DB::connection('aurora')->table('Attachment Bridge')->where('Subject', 'Part')
            ->update(['aiku_id' => null]);

        DB::connection('aurora')->table('Image Subject Bridge')->where('Image Subject Object', 'Part')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Part Dimension')->count();
        $count += DB::connection('aurora')->table('Part Deleted Dimension')->count();

        return $count;
    }

    protected function migrate(Tenant $tenant)
    {



        foreach (DB::connection('aurora')->table('Part Dimension')->get() as $auroraPartData) {
            $result = MigrateStock::run($auroraPartData);
            $this->recordAction($tenant, $result);
        }

        foreach (
            DB::connection('aurora')->table('Part Deleted Dimension')
                ->where('Part Deleted Key', '>', 0)
                ->get() as $auroraPartData
        ) {
            $result = MigrateDeletedStock::run($auroraPartData);
            $this->recordAction($tenant, $result);
        }
    }


}
