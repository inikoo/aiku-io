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
use App\Actions\Migrations\MigrateDeletedUser;
use App\Actions\Migrations\MigrateDeletedWorkshop;
use App\Actions\Migrations\MigrateSupplier;
use App\Actions\Migrations\MigrateUser;
use App\Actions\Migrations\MigrateWorkshop;
use App\Models\Account\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAgentTenant extends Command
{

    protected $signature = 'au_migration:agent {--reset} {--t|tenant= : Tenant slug} ';
    protected $description = 'Migrate aurora agent';
    private $tenant;

    public function handle(): int
    {
        $this->tenant = Tenant::where('code', $this->option('tenant'))->first();
        $this->tenant->makeCurrent();

        foreach ($this->tenant->data['aurora_agent'] as $agent_tenant_code => $agent_data) {
            $auroraAgentKey    = $agent_data[0];
            $auroraDatabase    = $agent_data[1];
            $auroraAccountCode = $agent_data[2];

            $this->setAuroraConnection($auroraDatabase);

            if ($this->option('reset')) {
                $this->reset($auroraAgentKey);
            }

            if ($agent_tenant_code === array_key_first($this->tenant->data['aurora_agent'])) {
                $this->migrateAgent($auroraAgentKey, $auroraAccountCode);

                foreach (
                    DB::connection('aurora')->table('Supplier Dimension')
                        ->leftJoin('Agent Supplier Bridge', 'Agent Supplier Supplier Key', '=', 'Supplier Key')
                        ->where('Agent Supplier Agent Key', $auroraAgentKey)
                        ->get() as $auData
                ) {
                    $auData->aurora_account = $auroraAccountCode;
                    MigrateSupplier::run($auData);
                }

                foreach (DB::connection('aurora')->table('Supplier Deleted Dimension')
                    ->leftJoin('Agent Supplier Bridge', 'Agent Supplier Supplier Key', '=', 'Supplier Deleted Key')
                    ->where('Agent Supplier Agent Key', $auroraAgentKey)
                    ->get() as $auData) {
                    $auData->aurora_account = $auroraAccountCode;
                    MigrateDeletedSupplier::run($auData);
                }
            } else {
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

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Agent Dimension')->count();
        $count += DB::connection('aurora')->table('Supplier Dimension')->count();
        $count += DB::connection('aurora')->table('Supplier Deleted Dimension')->count();
        $count += DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Agent', 'Supplier'])->count();
        $count += DB::connection('aurora')->table('User Deleted Dimension')->whereIn('User Deleted Type', ['Agent', 'Supplier'])->count();

        return $count;
    }

    protected function migrate_x(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Agent Dimension')->get() as $auData) {
            $result = MigrateAgentTenant::run($auData);
            $this->recordAction($tenant, $result);
        }

        foreach (DB::connection('aurora')->table('Supplier Dimension')->get() as $auData) {
            if ($auData->{'Supplier Production'} == 'Yes') {
                $result = MigrateWorkshop::run($auData);
            } else {
                $result = MigrateSupplier::run($auData);
            }
            $this->recordAction($tenant, $result);
        }
        foreach (DB::connection('aurora')->table('Supplier Deleted Dimension')->get() as $auData) {
            $auroraDeletedData = json_decode(gzuncompress($auData->{'Supplier Deleted Metadata'}));


            if (isset($auroraDeletedData->{'Supplier Production'}) and $auroraDeletedData->{'Supplier Production'} == 'Yes') {
                $result = MigrateDeletedWorkshop::run($auData);
            } else {
                $result = MigrateDeletedSupplier::run($auData);
            }
            $this->recordAction($tenant, $result);
        }

        foreach (
            DB::connection('aurora')->table('User Dimension')
                ->whereIn('User Type', ['Agent', 'Supplier'])
                ->get() as $auroraUserData
        ) {
            $this->results[$tenant->code]['models']++;
            $result = MigrateUser::run($auroraUserData);
            $this->recordAction($tenant, $result);
        }

        foreach (
            DB::connection('aurora')->table('User Deleted Dimension')
                ->whereIn('User Deleted Type', ['Agent', 'Supplier'])
                ->get() as $auroraUserData
        ) {
            $this->results[$tenant->code]['models']++;
            $result = MigrateDeletedUser::run($auroraUserData);
            $this->recordAction($tenant, $result);
        }
    }

    protected function reset_x()
    {
        DB::connection('aurora')->table('Agent Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Supplier Dimension')
            ->update(
                [
                    'aiku_id'          => null,
                    'aiku_workshop_id' => null,
                ]
            );
        DB::connection('aurora')->table('Supplier Deleted Dimension')
            ->update(
                [
                    'aiku_id'          => null,
                    'aiku_workshop_id' => null,
                ]
            );


        DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Agent', 'Supplier'])
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Agent', 'Supplier'])
            ->update(['aiku_token' => null]);
        DB::connection('aurora')->table('User Deleted Dimension')->whereIn('User Deleted Type', ['Staff', 'Contractor'])
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Attachment Bridge')->where('Subject', 'Supplier')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Image Subject Bridge')->whereIn('Image Subject Object', ['Agent', 'Supplier'])
            ->update(['aiku_id' => null]);
    }

}
