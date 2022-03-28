<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 15 Aug 2021 04:53:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\TenantsAdmin;

use App\Actions\Account\Tenant\StoreTenant;
use App\Actions\Auth\User\StoreUser;
use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Models\Account\Tenant;
use App\Models\Aiku\AppType;
use App\Models\Assets\Country;
use App\Models\Assets\Currency;
use App\Models\Assets\Language;
use App\Models\Assets\Timezone;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenant extends Command
{

    protected $signature = 'tenant:new {code} {--domain=} {--name=}  {--contact_name=} {--email=} {--type=ecommerce} {--randomPassword} {--country=GB} {--timezone=Europe/London} {--currency=GBP} {--language=en} {--aurora_db=} {--aurora_account_code=} {--aurora_agent=} {--workplace=}  ';

    protected $description = 'Create new tenant';

    public function handle(): int
    {
        if (!preg_match('/^[a-z]{1,16}$/', $this->argument('code'))) {
            $this->error("Invalid tenant code: {$this->argument('code')}");

            return 0;
        }

        $database = $this->argument('code').'_aiku';


        //todo make this database engine aware this only works with psql
        if (DB::connection('scaffolding')->table('pg_catalog.pg_database')->select('datname')->where('datname', $database)->first()) {
            $this->error("Database $database already exists");
            //return 0;
        } else {
            DB::connection('scaffolding')
                ->statement("CREATE DATABASE $database ENCODING 'UTF8' LC_COLLATE = 'en_GB.UTF-8' LC_CTYPE = 'en_GB.UTF-8' TEMPLATE template0");
        }

        $tenant = Tenant::where('code', $this->argument('code'))
            ->when($this->option('domain'), function ($query, $domain) {
                $query->where('domain', $domain);
            })
            ->first();
        if ($tenant) {
            $this->error("Tenant $tenant->code $tenant->domain already exists");

            return 0;
        }

        $data = [];

        if ($this->option('aurora_db')) {
            $data['aurora_db'] = $this->option('aurora_db');
        }
        if ($this->option('aurora_account_code')) {
            $data['aurora_account_code'] = $this->option('aurora_account_code');
        }

        if ($this->option('aurora_agent')) {
            $data['aurora_agent'] = json_decode($this->option('aurora_agent'), true);
        }

        $appType = AppType::where('code', $this->option('type'))->first();
        if (!$appType) {
            $this->error("Tenant type {$this->option('type')} not found");

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
            'code'         => $this->argument('code'),
            'type'         => $this->option('domain') ? 'subdomain' : 'jar',
            'domain'       => $this->option('domain') ? $this->option('domain') : null,
            'name'         => $this->option('name'),
            'contact_name' => $this->option('contact_name'),
            'email'        => $this->option('email'),
            'country_id'   => $country->id,
            'currency_id'  => $currency->id,
            'language_id'  => $language->id,
            'timezone_id'  => $timezone->id,
            'data'         => $data,

        ];

        $res    = StoreTenant::run($appType, $tenantData);
        $tenant = $res->model;

        if (Arr::get($tenant->data, 'aurora_db')) {
            $database_settings = data_get(config('database.connections'), 'aurora');
            data_set($database_settings, 'database', $this->option('aurora_db'));

            config(['database.connections.aurora' => $database_settings]);
            DB::connection('aurora');
            DB::purge('aurora');

            DB::connection('aurora')->table('Account Dimension')
                ->update(['aiku_id' => $tenant->id]);
        }


        $tenant->makeCurrent();

        DB::connection('tenant')->statement("CREATE COLLATION ci  (provider = icu, locale = 'und-u-ks-level2', deterministic = false)");

        Artisan::call('tenants:artisan "migrate:fresh --force --database=tenant" --tenant='.$tenant->id);
        Artisan::call('tenants:artisan "db:seed --force --class=JobPositionSeeder" --tenant='.$tenant->id);
        Artisan::call('tenants:artisan "db:seed --force --class=WorkScheduleSeeder" --tenant='.$tenant->id);


        StoreWorkplace::run($tenant,
                            [
                                'type'        => 'hq',
                                'name'        => $this->option('workplace') ?? 'headquarters',
                                'timezone_id' => $tenant->timezone_id
                            ]
        );


        $teamID=$tenant->appType->id;

        setPermissionsTeamId($teamID);

        if ($this->option('domain')) {
            $userData = [
                'username'         => 'admin',
                'middleware_group' => 'ecommerce'
            ];
        } else {
            $userData = [
                'jar_username'     => 'admin',
                'middleware_group' => $appType->code == 'ecommerce' ? 'jar_ecommerce' : $appType->code,

            ];
        }

        $res = StoreUser::run(
            userable: $tenant,
            userData: array_merge($userData, [

                          'tenant_id' => $tenant->id,
                          'password'  => Hash::make($password),
                          'admin'     => true,
                      ])

        );


        $res->model->syncRoles(['super-admin']);

        $this->line("Tenant $tenant->domain created :)");
        if ($this->option('randomPassword')) {
            $this->line("Tenant credentials");
        }

        $this->table(
            ['Tenant', 'Domain', 'Username', 'Password'],
            [

                [
                    $tenant->code,
                    $tenant->domain ?? 'app',
                    $res->model->username,
                    ($this->option('randomPassword') ? $password : '*****'),
                ]
            ]
        );


        return 0;
    }
}
