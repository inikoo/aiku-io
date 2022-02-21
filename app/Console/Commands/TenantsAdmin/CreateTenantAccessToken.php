<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 23:16:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\TenantsAdmin;

use App\Models\Account\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class CreateTenantAccessToken extends Command
{

    protected $signature = 'tenant:token {code} {token_name} {scopes?*} ';

    protected $description = 'Create new tenant access token';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($tenant = Tenant::firstWhere('code', $this->argument('code'))) {
            $tenant->makeCurrent();
            $token= $tenant->user->createToken($this->argument('token_name'),$this->argument('scopes'))->plainTextToken;

            if(Arr::get($tenant->data,'aurora_db')){

                $database_settings = data_get(config('database.connections'), 'aurora');
                data_set($database_settings, 'database', Arr::get($tenant->data,'aurora_db'));

                config(['database.connections.aurora' => $database_settings]);
                DB::connection('aurora');
                DB::purge('aurora');

                DB::connection('aurora')->table('Account Dimension')
                    ->where('aiku_id', $tenant->id)
                    ->update(['aiku_token' => $token]);
            }


            $this->line("Tenant access token: $token");
        } else {
            $this->error("Tenant not found: {$this->argument('code')}");
        }

        return 0;
    }
}
