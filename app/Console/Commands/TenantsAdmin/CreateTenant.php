<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 15 Aug 2021 04:53:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\TenantsAdmin;

use App\Actions\Account\AccountUser\StoreAccountUser;
use App\Actions\Account\Tenant\StoreTenant;
use App\Actions\System\User\StoreUser;
use App\Models\Account\BusinessType;
use App\Models\Account\Tenant;
use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenant extends Command
{

    protected $signature = 'tenant:new {domain} {name} {email} {nickname?} {username?} {--type=b2b} {--randomPassword} {--country=GB} {--timezone=Europe/London} {--currency=GBP} {--language=en} {--aurora_db=} {--aurora_account_code=}  ';

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

        if ($this->option('aurora_db')) {
            $data['aurora_db'] = $this->option('aurora_db');
        }
        if ($this->option('aurora_account_code')) {
            $data['aurora_account_code'] = $this->option('aurora_account_code');
        }


        $businessType = BusinessType::where('slug', $this->option('type'))->first();
        if (!$businessType) {
            $this->error("Business type {$this->option('type')} not found");

            return 0;
        }

        if ($this->option('randomPassword')) {
            $password = (config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(12), 4, '-', true));
        } else {
            $password = $this->secret('What is the password?');
            if (strlen($password) < 8) {
                $this->error("Password needs to be at least 8 characters");

                return 0;
            }
        }

        $country  = Country::where('code', $this->option('country'))->firstOrFail();
        $currency = Currency::where('code', $this->option('currency'))->firstOrFail();
        $language = Language::where('code', $this->option('language'))->firstOrFail();
        $timezone = Timezone::where('name', $this->option('timezone'))->firstOrFail();


        $tenantData = [
            'domain'      => $this->argument('domain'),
            'database'    => $database,
            'name'        => $this->argument('name'),
            'email'       => $this->argument('email'),
            'country_id'  => $country->id,
            'currency_id' => $currency->id,
            'language_id' => $language->id,
            'timezone_id' => $timezone->id,
            'data'        => $data,

        ];
        if ($this->argument('nickname')) {
            $tenantData['nickname'] = $this->argument('nickname');
        }
        $tenant = StoreTenant::run($businessType, $tenantData);


        $username = $tenant->nickname;
        if ($this->argument('username')) {
            $username = $this->argument('username');
        }


        StoreAccountUser::run($tenant,
                              [
                                  'username' => $username,
                                  'password' => Hash::make($password)
                              ]
        );


        $tenant->makeCurrent();
        Artisan::call('tenants:artisan "migrate:fresh --force --database=tenant" --tenant='.$tenant->id);
        Artisan::call('tenants:artisan "db:seed --force --class=PermissionSeeder" --tenant='.$tenant->id);

        $userPassword = (config('app.env') == 'local' ? 'hello' : wordwrap(Str::random(12), 4, '-', true));


         StoreUser::run($tenant,
                               [
                                   'username' => 'admin',
                                   'password' => Hash::make($userPassword),
                               ],
                               [
                                   'super-admin'
                               ]

        );




        $this->line("Tenant $tenant->domain created :)");
        if ($this->option('randomPassword')) {
            $this->line("Tenant credentials");
        }

        $this->table(
            ['Account', 'Slug', 'Username', 'Password'],
            [
                [
                    'tenant',
                    $tenant->slug,
                    $tenant->user->username,
                    ($this->option('randomPassword') ? $password : '*****'),
                ],
                [
                    'appAdmin',
                    '',
                    'admin',
                    $userPassword
                ]
            ]
        );


        return 0;
    }
}
