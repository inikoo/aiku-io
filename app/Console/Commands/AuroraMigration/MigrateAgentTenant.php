<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 20 Feb 2022 04:10:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateAgent;
use App\Actions\Migrations\MigrateDeletedSupplier;
use App\Actions\Migrations\MigrateSupplier;
use App\Actions\Migrations\MigrateSupplierHistoricProduct;
use App\Actions\Migrations\MigrateSupplierProduct;
use App\Models\Account\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAgentTenant extends Command
{

    protected $signature = 'au_migration:agent {--reset} {--t|tenant= : Tenant slug} ';
    protected $description = 'Migrate aurora agent';

    public function handle(): int
    {
        $tenant = Tenant::where('code', $this->option('tenant'))->first();
        $tenant->makeCurrent();

        foreach ($tenant->data['aurora_agent'] as $agent_tenant_code => $agent_data) {
            $auroraAgentKey    = $agent_data[0];
            $auroraDatabase    = $agent_data[1];
            $auroraAccountCode = $agent_data[2];

            $this->setAuroraConnection($auroraDatabase);

            if ($this->option('reset')) {
                $this->reset($auroraAgentKey);
            }

            if ($agent_tenant_code === array_key_first($tenant->data['aurora_agent'])) {
                $this->migrateAgent($auroraAgentKey, $auroraAccountCode);

                foreach (
                    DB::connection('aurora')->table('Supplier Dimension')
                        ->leftJoin('Agent Supplier Bridge', 'Agent Supplier Supplier Key', '=', 'Supplier Key')
                        ->where('Agent Supplier Agent Key', $auroraAgentKey)
                        ->get() as $auData
                ) {
                    $auData->aurora_account = $auroraAccountCode;
                    MigrateSupplier::run($auData);

                    DB::connection('aurora')
                        ->table('Supplier Part Dimension')
                        ->where('Supplier Part Supplier Key', $auData->{'Supplier Key'})
                        ->orderBy('Supplier Part Key')
                        ->chunk(100, function ($chunk) use ($auroraAccountCode, $auData) {
                            foreach ($chunk as $auroraData) {
                                $auroraData->aurora_account = $auroraAccountCode;
                                $auroraData->agentMigration = true;
                                MigrateSupplierProduct::run($auroraData);

                                foreach (
                                    DB::connection('aurora')
                                        ->table('Supplier Part Historic Dimension')
                                        ->where('Supplier Part Historic Supplier Part Key', '=', $auroraData->{'Supplier Part Key'})
                                        ->get() as $auroraHistoricData
                                ) {
                                    $auroraHistoricData->aurora_account = $auroraAccountCode;
                                    $auroraHistoricData->agentMigration = true;
                                    MigrateSupplierHistoricProduct::run($auroraHistoricData);
                                }
                            }
                        });
                }

                foreach (
                    DB::connection('aurora')->table('Supplier Deleted Dimension')
                        ->leftJoin('Agent Supplier Bridge', 'Agent Supplier Supplier Key', '=', 'Supplier Deleted Key')
                        ->where('Agent Supplier Agent Key', $auroraAgentKey)
                        ->get() as $auData
                ) {
                    $auData->aurora_account = $auroraAccountCode;
                    MigrateDeletedSupplier::run($auData);
                }
            }
        }


        return 0;
    }

    protected function migrateAgent($auroraAgentKey, $auroraAccountCode)
    {
        foreach (DB::connection('aurora')->table('Agent Dimension')->where('Agent Key', $auroraAgentKey)->get() as $auData) {
            $auData->aurora_account = $auroraAccountCode;
            MigrateAgent::run($auData);
        }
    }

    private function setAuroraConnection($database_name)
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database_name);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');
    }

    protected function reset($auroraAgentKey)
    {
        DB::connection('aurora')->table('Agent Dimension')->where('Agent Key', $auroraAgentKey)
            ->update(['aiku_id' => null]);


        foreach (
            DB::connection('aurora')->table('Supplier Dimension')
                ->leftJoin('Agent Supplier Bridge', 'Agent Supplier Supplier Key', '=', 'Supplier Key')
                ->where('Agent Supplier Agent Key', $auroraAgentKey)->get() as $auData
        ) {
            DB::connection('aurora')->table('Supplier Dimension')->where('Supplier Key', $auData->{'Supplier Key'})
                ->update(['aiku_id' => null]);
            DB::connection('aurora')->table('Supplier Part Dimension')->where('Supplier Part Supplier Key', $auData->{'Supplier Key'})
                ->update(['aiku_supplier_id' => null]);
        }

        foreach (
            DB::connection('aurora')->table('Supplier Deleted Dimension')
                ->leftJoin('Agent Supplier Bridge', 'Agent Supplier Supplier Key', '=', 'Supplier Deleted Key')
                ->where('Agent Supplier Agent Key', $auroraAgentKey)->get() as $auData
        ) {
            DB::connection('aurora')->table('Supplier Deleted Dimension')->where('Supplier Deleted Key', $auData->{'Supplier Deleted Key'})
                ->update(['aiku_id' => null]);
        }
    }



}
