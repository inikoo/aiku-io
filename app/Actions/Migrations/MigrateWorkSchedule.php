<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 03:04:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\HumanResources\WorkSchedule\StoreWorkSchedule;
use App\Models\HumanResources\WorkSchedule;
use Carbon\Carbon;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;


class MigrateWorkSchedule extends MigrateModel
{
    use AsAction;


    protected function handle($auModel): ActionResult
    {
        $res  = new ActionResult();
        $date = Carbon::parse($auModel->{'Timesheet Date'});
        if ($auModel->scheduleMarks->count() == 0) {
            if ($date->isWeekend()) {
                $res->model    = WorkSchedule::firstWhere('type', 'rest-day');
                if($res->model->created_at->gt($date)){
                    $res->model->created_at=$date;
                    $res->model->save();
                }

                $res->model_id = $res->model->id;
            }

            return $res;
        } else {
            $this->modelData = [];
            $start           = null;
            $end             = null;
            $break_start     = null;
            $breaks          = [];
            foreach ($auModel->scheduleMarks as $mark) {
                $markDate = Carbon::parse($mark->{'Timesheet Record Date'})->format('H:i:s');

                if ($mark->{'Timesheet Record Type'} == 'WorkingHoursMark') {
                    if ($start) {
                        $end = $markDate;
                    } else {
                        $start = $markDate;
                    }
                } elseif ($mark->{'Timesheet Record Type'} == 'BreakMark') {
                    if ($break_start) {
                        $breaks[]    = [$break_start, $markDate];
                        $break_start = null;
                    } else {
                        $break_start = $markDate;
                    }
                }
            }


            $workSchedule            = new WorkSchedule();
            $workSchedule->starts_at = $start;
            $workSchedule->ends_at   = $end;
            $workSchedule->breaks    = $breaks;


            if ($workSchedule = WorkSchedule::where('checksum', $workSchedule->getChecksum())->where('type', 'work-day')->first()) {


                if($workSchedule->created_at->gt($date)){
                    $workSchedule->created_at=$date;
                    $workSchedule->save();
                }
                $res->model    = $workSchedule;
                $res->model_id = $res->model->id;

                return $res;
            }

            return StoreWorkSchedule::run([
                                              'starts_at' => $start,
                                              'ends_at'   => $end,
                                              'breaks'    => $breaks,
                                              'type'      => 'work-day',
                                              'created_at' => $auModel->{'Timesheet Date'}
                                          ]);
        }
    }


}


