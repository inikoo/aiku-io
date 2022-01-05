<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 15:55:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkSchedule;


use App\Models\HumanResources\WorkSchedule;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWorkSchedule
{
    use AsAction;
    use WithUpdate;

    public function handle(WorkSchedule $workSchedule,  array $workScheduleData): ActionResult
    {
        $res = new ActionResult();



        $workSchedule->update(
            Arr::except($workScheduleData, [
                'breaks',


            ])
        );
        $workSchedule->update($this->extractJson($workScheduleData, ['breaks']));

        $res->changes = array_merge($res->changes, $workSchedule->getChanges());




        $res->model = $workSchedule;

        $res->model_id = $workSchedule->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }



}
