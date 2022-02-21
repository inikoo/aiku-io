<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 00:04:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateClockingMachine;
use App\Actions\Migrations\MigrateDeletedEmployee;
use App\Actions\Migrations\MigrateDeletedGuest;
use App\Actions\Migrations\MigrateDeletedUser;
use App\Actions\Migrations\MigrateEmployee;
use App\Actions\Migrations\MigrateGuest;
use App\Actions\Migrations\MigrateUser;
use App\Actions\Migrations\MigrateWorkTarget;
use App\Models\Account\Tenant;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Facades\DB;


class MigrateHR extends MigrateAurora
{


    protected $signature = 'au_migration:hr {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora human resources';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Staff Dimension')
            ->update(
                [
                    'aiku_id'       => null,
                    'aiku_guest_id' => null
                ]
            );
        DB::connection('aurora')->table('Staff Deleted Dimension')
            ->update(
                [
                    'aiku_id'       => null,
                    'aiku_guest_id' => null
                ]
            );
        DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Staff', 'Contractor'])
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Staff', 'Contractor'])
            ->update(['aiku_token' => null]);
        DB::connection('aurora')->table('User Deleted Dimension')->whereIn('User Deleted Type', ['Staff', 'Contractor'])
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Attachment Bridge')->where('Subject', 'Staff')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Image Subject Bridge')->where('Image Subject Object', 'Staff')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Timesheet Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Timesheet Record Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Clocking Machine Dimension')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Staff Dimension')->count();
        $count += DB::connection('aurora')->table('Staff Deleted Dimension')->count();
        $count += DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Staff', 'Contractor'])->count();
        $count += DB::connection('aurora')->table('User Deleted Dimension')->whereIn('User Deleted Type', ['Staff', 'Contractor'])->count();
        $count += DB::connection('aurora')->table('Timesheet Dimension')->where('Timesheet Staff Key', '>', 0)->count();

        return $count;
    }


    protected function migrate(Tenant $tenant)
    {
        foreach (
            DB::connection('aurora')
                ->table('Clocking Machine Dimension')
                ->get() as $auroraData
        ) {
            MigrateClockingMachine::run($auroraData);
        }

        foreach (
            DB::connection('aurora')
                ->table('Staff Dimension')
                ->get() as $auroraData
        ) {
            $this->results[$tenant->code]['models']++;
            if ($auroraData->{'Staff Type'} == 'Contractor') {
                $result = MigrateGuest::run($auroraData);
            } else {
                $result = MigrateEmployee::run($auroraData);
            }
            $this->recordAction($tenant, $result);
        }

        foreach (DB::connection('aurora')->table('Staff Deleted Dimension')->get() as $auroraData) {
            $this->results[$tenant->code]['models']++;
            if ($auroraData->{'Staff Deleted Type'} == 'Contractor') {
                $result = MigrateDeletedGuest::run($auroraData);
            } else {
                $result = MigrateDeletedEmployee::run($auroraData);
            }
            $this->recordAction($tenant, $result);
        }

        foreach (
            DB::connection('aurora')->table('User Dimension')
                ->whereIn('User Type', ['Staff', 'Contractor'])
                ->get() as $auroraUserData
        ) {
            $this->results[$tenant->code]['models']++;
            $result = MigrateUser::run($auroraUserData);
            $this->recordAction($tenant, $result);
        }

        foreach (
            DB::connection('aurora')->table('User Deleted Dimension')
                ->whereIn('User Deleted Type', ['Staff', 'Contractor'])
                ->get() as $auroraUserData
        ) {
            $this->results[$tenant->code]['models']++;
            $result = MigrateDeletedUser::run($auroraUserData);
            $this->recordAction($tenant, $result);
        }

        foreach (
            DB::connection('aurora')
                ->table('Staff Supervisor Bridge')
                ->get() as $auroraData
        ) {
            $employee = Employee::firstWhere('aurora_id', $auroraData->{'Staff Key'});

            $supervisor = Employee::firstWhere('aurora_id', $auroraData->{'Supervisor Key'});

            if ($employee and $supervisor) {
                $employee->supervisors()->sync([$supervisor->id]);
            }
        }


        DB::connection('aurora')->table('Timesheet Dimension')
            ->where('Timesheet Staff Key', '>', 0)
            ->orderBy('Timesheet Date')->chunk(1000, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {
                    $result = MigrateWorkTarget::run($auroraData);
                    $this->recordAction($tenant, $result);
                }
            });
    }


}
