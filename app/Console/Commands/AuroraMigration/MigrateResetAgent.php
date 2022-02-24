<?php
/*
*  Author: Raul Perusquia <raul@inikoo.com>
*  Created: Mon, 21 Feb 2022 19:35:25 Malaysia Time, Kuala Lumpur, Malaysia
*  Copyright (c) 2022, Inikoo
*  Version 4.0
*/

/** @noinspection ALL */


namespace App\Console\Commands\AuroraMigration;


use App\Models\Account\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MigrateResetAgent extends Command
{
    protected $signature = 'au_migration:reset_agent  ';
    protected $description = 'Reset migrate aurora customers';

    private function setAuroraConnection($database_name)
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database_name);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');
    }

    public function handle(): int
    {
        $tenants = Tenant::whereNotNull('data->aurora_db')->get();


        $tenants->each(function ($tenant) {
            $tenant->makeCurrent();
            if (Arr::get($tenant->data, 'aurora_db')) {
                $this->setAuroraConnection($tenant->data['aurora_db']);

                DB::connection('aurora')->table('Part Dimension')
                    ->update(['aiku_agent_unit_id' => null]);

                DB::connection('aurora')->table('Supplier Part Dimension')
                    ->update(['aiku_agent_unit_id' => null]);
                DB::connection('aurora')->table('Supplier Part Historic Dimension')
                    ->update(['aiku_agent_unit_id' => null]);
            }
        });


        return 0;
    }


}
