<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Mar 2022 02:28:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateDeletedEmployee;
use App\Actions\Migrations\MigrateDeletedGuest;
use App\Actions\Migrations\MigrateEmployee;
use App\Actions\Migrations\MigrateGuest;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use Illuminate\Support\Facades\DB;

trait GetWorkSubject
{


    public function getWorkSubject($auroraId): null|Employee|Guest
    {
        $subject = Employee::withTrashed()->firstWhere('aurora_id', $auroraId);
        if (!$subject) {
            $subject = Guest::withTrashed()->firstWhere('aurora_id', $auroraId);
        }
        if (!$subject) {

            foreach (
                DB::connection('aurora')
                    ->table('Staff Dimension')
                    ->where('Staff Key', $auroraId)
                    ->get() as $auroraData
            ) {

                if ($auroraData->{'Staff Type'} == 'Contractor') {
                    $_res = MigrateGuest::run($auroraData);
                } else {
                    $_res = MigrateEmployee::run($auroraData);
                }

                $subject=$_res->model;

            }
        }
        if (!$subject) {

            foreach (DB::connection('aurora')->table('Staff Deleted Dimension')
                ->where('Staff Deleted Key', $auroraId)
                ->get() as $auroraData) {
                if ($auroraData->{'Staff Deleted Type'} == 'Contractor') {
                    $_res = MigrateDeletedGuest::run($auroraData);
                } else {
                    $_res = MigrateDeletedEmployee::run($auroraData);
                }
                $subject=$_res->model;

            }

        }




        return $subject;
    }

}


