<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 10 Mar 2022 02:47:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\TimeTracking;

use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use App\Models\HumanResources\TimeTracking;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTimeTracking
{
    use AsAction;


    public function handle(Employee|Guest $subject,  array $modelData): ActionResult
    {
        $res = new ActionResult();



        /** @var TimeTracking $timeTracking */
        $timeTracking = $subject->timeTrackings()->create($modelData);


        $res->model    = $timeTracking;
        $res->model_id = $timeTracking->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';


        return $res;
    }


}
