<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 19 Dec 2021 18:05:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployeeJobPositions
{
    use AsAction;
    use WithUpdate;

    public function handle(Employee $employee,string $operation, array $ids): ActionResult
    {
        $res = new ActionResult();


        switch ($operation) {
            case 'sync':

                $result=$employee->jobPositions()->sync($ids);

                if($result['attached'] ){
                    $res->changes['attached']=$result['attached'];
                }
                if($result['detached'] ){
                    $res->changes['detached']=$result['detached'];
                }


                break;
            case 'attach':


                //todo do this properly
                $oldCount=$employee->jobPositions()->count();
                $employee->jobPositions()->attach($ids);
                if($oldCount<$employee->jobPositions()->count()){
                    $res->changes['attached']=$ids;
                }

                break;
            case 'detach':
                $employee->jobPositions()->detach($ids);

                //todo do this properly
                $oldCount=$employee->jobPositions()->count();
                $employee->jobPositions()->attach($ids);
                if($oldCount>$employee->jobPositions()->count()){
                    $res->changes['detached']=$ids;
                }
                break;

        }


        $res->model = $employee;
        $res->model_id = $employee->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }


}
