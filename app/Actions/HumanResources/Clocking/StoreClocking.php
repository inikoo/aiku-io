<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 05 Jan 2022 15:29:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Clocking;

use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Employee;
use App\Models\System\Guest;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreClocking
{
    use AsAction;


    public function handle(Employee|Guest $clockable, ClockingMachine|Employee $creator, array $clockingData): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\HumanResources\Clocking $clocking */



        $clockingData['creator_type']=class_basename($creator);
        $clockingData['creator_id']=$creator->id;


        $clocking      = $clockable->clockings()->create($clockingData);



        $res->model    = $clocking;
        $res->model_id = $clocking->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';


        return $res;
    }


}
