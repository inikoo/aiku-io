<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 02 Jan 2022 00:48:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkTarget;

use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use App\Models\Utils\ActionResult;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWorkTarget
{
    use AsAction;


    public function handle(Employee|Guest $subject, array $workTargetData): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\HumanResources\WorkTarget $workTarget */

     //   try {
            $workTarget    = $subject->workTargets()->create($workTargetData);
            $res->model    = $workTarget;
            $res->model_id = $workTarget->id;
            $res->status   = $res->model_id ? 'inserted' : 'error';
       // } catch (Exception) {
      //      $res->status = 'error';
     //   }


        return $res;
    }


}
