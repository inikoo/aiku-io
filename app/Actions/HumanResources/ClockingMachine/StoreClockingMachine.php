<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 04 Jan 2022 05:38:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\ClockingMachine;

use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreClockingMachine
{
    use AsAction;


    public function handle(Workplace $workplace, array $clockingMachineData): ActionResult
    {
        $res = new ActionResult();

        /** @var ClockingMachine $clockingMachine */
            $clockingMachine  = $workplace->clockingMachines()->create($clockingMachineData);
            $res->model    = $clockingMachine;
            $res->model_id = $clockingMachine->id;
            $res->status   = $res->model_id ? 'inserted' : 'error';



        return $res;
    }


}
