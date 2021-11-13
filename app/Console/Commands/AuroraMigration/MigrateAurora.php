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
    protected ProgressBar $bar;


    public function __construct()
    {
        parent::__construct();
    }

    private function setAuroraConnection($database_name)
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

    protected function migrateLandlord()
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
                $this->setAuroraConnection($tenant->data['aurora_db']);
                $this->total += $this->count();
            }
        });


        $this->bar = $this->output->createProgressBar($this->total);
        $this->bar->setFormat('debug');
        $this->bar->start();
    }

    protected function handleMigration()
    {
        $this->info($this->description);

        $this->results = [];

        if (!$this->option('all') and count($this->option('tenant')) == 0) {
            $this->error('Provide tenant --tenant or --all');
        }

        if ($this->option('all')) {
            $tenants = Tenant::whereNotNull('data->aurora_db')->get();
        } else {
            $tenants = Tenant::whereIn('nickname', $this->option('tenant'))->get();
        }

        $this->startProgressBar($tenants);


        $tenants->each(function ($tenant) {
            $tenant->makeCurrent();
            if (Arr::get($tenant->data, 'aurora_db')) {
                $this->results[$tenant->nickname] = [
                    'tenant'   => $tenant->nickname,
                    'models'   => 0,
                    'inserted' => 0,
                    'updated'  => 0,
                    'errors'   => 0
                ];

                $this->setAuroraConnection($tenant->data['aurora_db']);
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
        $this->results[$tenant->nickname]['models']++;
        switch ($result->status) {
            case 'inserted':
                $this->results[$tenant->nickname]['inserted'] += 1;
                break;
            case 'updated':
                $this->results[$tenant->nickname]['updated'] += 1;
                break;
            case 'error':
                $this->results[$tenant->nickname]['errors'] += 1;
                break;
        }

        $this->bar->advance();
    }

    protected function handleLandlordMigration()
    {
        $this->info($this->description);

        $this->results = [];

        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', 'kbase');
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');


        $this->total = $this->count();


        $this->bar = $this->output->createProgressBar($this->total);
        $this->bar->setFormat('debug');
        $this->bar->start();

        $this->results = [
            'models'   => 0,
            'inserted' => 0,
            'updated'  => 0,
            'errors'   => 0
        ];

        if ($this->option('reset')) {
            if (config('app.env') == 'production') {
                if ($this->confirm('Do you really want to reset?')) {
                    $this->reset();
                }
            } else {
                $this->reset();
            }
        }


        $this->migrateLandlord();

        $this->showLandlordResults();
    }

    protected function recordLandlordAction($result)
    {
        $this->results['models']++;
        switch ($result->status) {
            case 'inserted':
                $this->results['inserted'] += 1;
                break;
            case 'updated':
                $this->results['updated'] += 1;
                break;
            case 'error':
                $this->results['errors'] += 1;
                break;
        }

        $this->bar->advance();
    }

    protected function showLandlordResults()
    {
        $this->bar->finish();
        $this->info('');
        $this->table(
            ['Models', 'Inserted', 'Updated', 'Errors'],
            [$this->results]
        );
    }


}


