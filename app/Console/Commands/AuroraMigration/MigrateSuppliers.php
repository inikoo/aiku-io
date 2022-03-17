<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 08 Oct 2021 18:08:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateDeletedSupplier;
use App\Actions\Migrations\MigrateDeletedUser;
use App\Actions\Migrations\MigrateDeletedWorkshop;
use App\Actions\Migrations\MigrateSupplier;
use App\Actions\Migrations\MigrateUser;
use App\Actions\Migrations\MigrateWorkshop;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateSuppliers extends MigrateAurora
{

    protected $signature = 'au_migration:suppliers {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora suppliers ';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Supplier Dimension')->whereNotIn('Supplier Type', ['Agent'])
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


        DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Supplier'])
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Supplier'])
            ->update(['aiku_token' => null]);
        DB::connection('aurora')->table('User Deleted Dimension')->whereIn('User Deleted Type', ['Staff', 'Contractor'])
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Attachment Bridge')->where('Subject', 'Supplier')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Image Subject Bridge')->whereIn('Image Subject Object', ['Supplier'])
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Supplier Dimension')->whereNotIn('Supplier Type', ['Agent'])->count();
        $count += DB::connection('aurora')->table('Supplier Deleted Dimension')->count();
        $count += DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Supplier'])->count();
        $count += DB::connection('aurora')->table('User Deleted Dimension')->whereIn('User Deleted Type', ['Supplier'])->count();

        return $count;
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (
            DB::connection('aurora')->table('Supplier Dimension')
                ->leftJoin('Agent Supplier Bridge', 'Agent Supplier Supplier Key','Supplier Key')
                ->whereNull('Agent Supplier Agent Key')
                ->get() as $auNotAgentData
        ) {
            foreach (
                DB::connection('aurora')->table('Supplier Dimension')
                    ->where('Supplier Key', $auNotAgentData->{'Supplier Key'})->get() as $auData
            ) {
                if ($auData->{'Supplier Production'} == 'Yes') {
                    $result = MigrateWorkshop::run($auData);
                } else {
                    $result = MigrateSupplier::run($auData);
                }
                $this->recordAction($tenant, $result);
            }
            foreach (
                DB::connection('aurora')->table('Supplier Deleted Dimension')
                    ->where('Supplier Deleted Key', $auNotAgentData->{'Supplier Key'})
                    ->get() as $auData
            ) {
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
                    ->where('User Type', 'Supplier')->where('User Parent Key',$auNotAgentData->{'Supplier Key'})
                    ->get() as $auroraUserData
            ) {
                $this->results[$tenant->code]['models']++;
                $result = MigrateUser::run($auroraUserData);
                $this->recordAction($tenant, $result);
            }






        }
        foreach (
            DB::connection('aurora')->table('User Deleted Dimension')
                ->whereIn('User Deleted Type', ['Supplier'])
                ->get() as $auroraUserData
        ) {
            $this->results[$tenant->code]['models']++;
            $result = MigrateDeletedUser::run($auroraUserData);
            $this->recordAction($tenant, $result);
        }


    }


}
