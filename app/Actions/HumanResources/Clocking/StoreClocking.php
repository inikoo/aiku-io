<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 05 Jan 2022 15:29:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Clocking;

use App\Actions\HumanResources\TimeTracking\StoreTimeTracking;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use App\Models\Utils\ActionResult;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreClocking
{
    use AsAction;


    public function handle(Employee|Guest $subject, ClockingMachine|Guest|Employee|null $created_by, array $clockingData): ActionResult
    {
        $res = new ActionResult();




        if (!$timeTracking = $subject->timeTrackings()
            ->whereNotNull('start_clocking_id')
            ->whereNull('end_clocking_id')
            ->where('starts_at', '>', Carbon::parse($clockingData['clocked_at'])->subHours(16))
            ->when($clockingData['workplace_id'], function ($query, $workplaceID) {
                $query->where('workplace_id',$workplaceID);
            }, function ($query) {
                $query->whereNull('workplace_id');
            })
            ->latest('start_clocking_id')
            ->first()) {
            $timeTrackingRes = StoreTimeTracking::run($subject, ['workplace_id' => Arr::get($clockingData,'workplace_id')]);
            $timeTracking    = $timeTrackingRes->model;
        }



        $clockingData['time_tracking_id'] = $timeTracking->id;
        if ($created_by) {
            $clockingData['created_by_type'] = class_basename($created_by);
            $clockingData['created_by_id']   = $created_by->id;
        }
        /** @var \App\Models\HumanResources\Clocking $clocking */
        $clocking = $subject->clockings()->create($clockingData);


        $res->model    = $clocking;
        $res->model_id = $clocking->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';


        return $res;
    }


}
