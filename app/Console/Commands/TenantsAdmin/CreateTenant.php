<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 15 Aug 2021 04:53:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\TenantsAdmin;

use App\Models\Aiku\BusinessType;
use App\Models\Aiku\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenant extends Command
{

    protected $signature = 'tenant:new {domain} {name} {--type=b2b} {--legacy_db=}';

    protected $description = 'Create new tenant';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $database = strtolower(preg_replace('/\..*/i', '', $this->argument('domain'))).'_aiku';

        if (DB::connection('scaffolding')->table('INFORMATION_SCHEMA.SCHEMATA')->select('SCHEMA_NAME')->where('SCHEMA_NAME', $database)->first()) {
            $this->error("Database $database already exists");
            //return 0;
        } else {
            DB::connection('scaffolding')->statement("CREATE DATABASE $database CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
        }


        $tenant = Tenant::where('domain', $this->argument('domain'))->where('database', $database)->first();
        if ($tenant) {
            $this->error("Tenant $tenant->domain $tenant->domain already exists");

            return 0;
        }

        $data = [];
        if ($this->option('legacy_db')) {
            $data['legacy_db'] = $this->option('legacy_db');
        }


        $business_type = BusinessType::where('slug', $this->option('type'))->first();
        if (!$business_type) {
            $this->error("Business type {$this->option('type')} not found");

            return 0;
        }


        $tenant = new Tenant([
                                 'domain'   => $this->argument('domain'),
                                 'database' => $database,
                                 'name'     => $this->argument('name'),
                                 'data'     => $data
                             ]);


        $business_type->tenants()->save($tenant);
        $this->line("Tenant $tenant->domain created :)");
        $tenant->makeCurrent();
        Artisan::call('tenants:artisan "migrate:fresh --force --database=tenant" --tenant='.$tenant->id);
        Artisan::call('tenants:artisan "db:seed --force --class=PermissionSeeder" --tenant='.$tenant->id);
        Artisan::call('tenants:artisan "db:seed --force --class=RootUserSeeder" --tenant='.$tenant->id);
        $this->line("Tenant $tenant->domain seeded");


        return 0;
    }
}
