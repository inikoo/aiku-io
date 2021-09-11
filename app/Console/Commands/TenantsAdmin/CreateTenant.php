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
use App\Models\System\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenant extends Command
{

    protected $signature = 'tenant:new {domain} {name} {email} {--slug} {--type=b2b} {--legacy_db=} {--randomPassword}';

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


        $businessType = BusinessType::where('slug', $this->option('type'))->first();
        if (!$businessType) {
            $this->error("Business type {$this->option('type')} not found");

            return 0;
        }

        if ($this->option('randomPassword')) {
            $password = wordwrap(Str::random(12), 4, '-', true);
        } else {
            $password = $this->secret('What is the password?');
        }


        $tenant = new Tenant([
                                 'domain'   => $this->argument('domain'),
                                 'database' => $database,
                                 'name'     => $this->argument('name'),
                                 'email'    => $this->argument('email'),
                                 'password' => $password,
                                 'data'     => $data
                             ]);


        if ($this->option('slug')) {
            $tenant->slug = $this->option('slug');
        }


        $businessType->tenants()->save($tenant);

        $tenant->makeCurrent();
        Artisan::call('tenants:artisan "migrate:fresh --force --database=tenant" --tenant='.$tenant->id);
        Artisan::call('tenants:artisan "db:seed --force --class=PermissionSeeder" --tenant='.$tenant->id);

        $appAdminPassword = (config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(12), 4, '-', true));


        $appAdmin       = new User([
                                       'email'    => $this->argument('email'),
                                       'password' => Hash::make($appAdminPassword),
                                   ]);
        $appAdmin->slug = 'admin';


        $tenant->appAdmin()->save($appAdmin);
        $appAdmin->assignRole('super-admin');

        $this->line("Tenant $tenant->domain created :)");
        if ($this->option('randomPassword')) {
            $this->line("Tenant credentials ");
        }

        $this->table(
            ['Account', 'Username', 'Email'],
            [
                [
                    'tenant',
                    $this->argument('email'),
                    ($this->option('randomPassword') ? $password : '*****'),
                ],
                [
                    'appAdmin',
                    'admin',
                    $appAdminPassword
                ]
            ]
        );


        return 0;
    }
}
