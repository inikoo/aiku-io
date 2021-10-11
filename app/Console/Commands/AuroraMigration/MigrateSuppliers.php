<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 08 Oct 2021 18:08:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateAgent;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateSuppliers extends MigrateAurora
{

    protected $signature = 'au_migration:suppliers {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora suppliers';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Agent Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Supplier Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Supplier Part Dimension')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Agent Dimension')->count();
        $count += DB::connection('aurora')->table('Supplier Dimension')->count();
        $count += DB::connection('aurora')->table('Supplier Part Dimension')->count();

        return $count;
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Agent Dimension')->get() as $auData) {
            $result = MigrateAgent::run($auData);
            $this->recordAction($tenant, $result);


        }
    }


}
