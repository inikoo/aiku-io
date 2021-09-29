<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 17:59:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Models\Account\Tenant;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class MigrateAurora extends Command
{

    protected array $results;
    protected $signature = 'au_migration:base';
    protected $description = 'Migrate aurora';
    protected int $total = 0;
    private ProgressBar $bar;


    public function __construct()
    {
        parent::__construct();
    }

    private function set_aurora_connection($database_name)
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database_name);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');
    }

    protected function reset()
    {
    }

    protected function migrate(Tenant $tenant)
    {
    }

    protected function showResults()
    {
        $this->bar->finish();
        $this->info('');
        $this->table(
            ['Tenant', 'Models', 'Inserted', 'Updated', 'Errors'],
            $this->results
        );
    }

    protected function count(): int
    {
        return 0;
    }

    private function startProgressBar($tenants)
    {
        $tenants->each(function ($tenant) {
            if (Arr::get($tenant->data, 'aurora_db')) {
                $this->set_aurora_connection($tenant->data['aurora_db']);
                $this->total += $this->count();
            }
        });


        $this->bar = $this->output->createProgressBar($this->total);
        $this->bar->setFormat('debug');
        $this->bar->start();
    }

    protected function handleMigration()
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

        $this->startProgressBar($tenants);


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

    protected function recordAction(Tenant $tenant, $result)
    {
        $this->results[$tenant->slug]['models']++;
        $this->results[$tenant->slug]['updated']  += $result['updated'];
        $this->results[$tenant->slug]['inserted'] += $result['inserted'];
        $this->results[$tenant->slug]['errors']   += $result['errors'];
        $this->bar->advance();
    }


}


