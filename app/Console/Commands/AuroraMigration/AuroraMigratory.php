<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 17:59:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Models\Aiku\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait AuroraMigratory
{

    private function set_aurora_connection($database_name)
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database_name);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');
    }

    private function sanitizeData($date): array
    {
        return array_filter($date, fn($value) => !is_null($value) && $value !== ''
            && $value != '0000-00-00 00:00:00'
            && $value != '2018-00-00 00:00:00'
        );
    }

    private function getDate($value): string
    {
       return ($value !== '' && $value != '0000-00-00 00:00:00'
           && $value != '2018-00-00 00:00:00' ) ?  Carbon::parse($value)->format('Y-m-d') : '';
    }


    private function showResults()
    {
        $this->table(
            ['Tenant', 'Models', 'Inserted', 'Updated', 'Errors'],
            $this->results
        );
    }

    private function handleMigration()
    {
        $this->results = [];

        if (!$this->option('all') and count($this->option('tenant')) == 0) {
            $this->error('Provide tenant --tenant or --all');
        }

        if ($this->option('all')) {
            $tenants = Tenant::whereNotNull('data->aurora_db')->get();
        } else {
            $tenants = Tenant::whereIn('slug', $this->option('tenant'))->get();
        }

        $tenants->each(function ($tenant) {
            $tenant->makeCurrent();
            if (Arr::get($tenant->data, 'aurora_db')) {
                $this->results[$tenant->slug] = [
                    'tenant'   => $tenant->slug,
                    'models'   => 0,
                    'inserted' => 0,
                    'updated'  => 0,
                    'errors'   => 0
                ];

                $this->set_aurora_connection($tenant->data['aurora_db']);
                if ($this->option('reset')) {
                    if (config('app.env') == 'production') {
                        if ($this->confirm('Do you really want to reset?')) {
                            $this->reset();
                        }
                    } else {
                        $this->reset();
                    }
                }


                $this->migrate($tenant);
            }
        });

        $this->showResults();
    }

}


