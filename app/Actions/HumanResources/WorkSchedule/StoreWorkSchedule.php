<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 15:47:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkSchedule;

use App\Models\HumanResources\WorkSchedule;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;
use Exception;

class StoreWorkSchedule
{
    use AsAction;


    public function handle(array $workScheduleData): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\HumanResources\WorkSchedule $workSchedule */

        try {
            $workSchedule  = WorkSchedule::create($workScheduleData);
            $res->model    = $workSchedule;
            $res->model_id = $workSchedule->id;
            $res->status   = $res->model_id ? 'inserted' : 'error';
        } catch (Exception) {
            $res->status = 'error';
        }


        return $res;
    }


}
