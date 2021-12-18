<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Dec 2021 15:17:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployeeRelationships
{
    use AsAction;
    use WithUpdate;

    public function handle(Employee $employee, string $type, string $operation, array $relatedEmployeeIds): ActionResult
    {
        $res = new ActionResult();


        if($type=='supervisor'){
            $relation=$employee->supervisors();
        }else{
            $relation=$employee->team();
        }



        switch ($operation) {
            case 'sync':
                if (($key = array_search($employee->id, $relatedEmployeeIds)) !== false) {
                    unset($relatedEmployeeIds[$key]);
                }
                $result=$relation->sync($relatedEmployeeIds);

                if($result['attached'] ){
                    $res->changes['attached_'.$type]=$result['attached'];
                }
                if($result['detached'] ){
                    $res->changes['detached_'.$type]=$result['detached'];
                }


                break;
            case 'attach':

                if (($key = array_search($employee->id, $relatedEmployeeIds)) !== false) {
                    unset($relatedEmployeeIds[$key]);
                }
                //todo do this properly
                $oldCount=$relation->count();
                $relation->attach($relatedEmployeeIds);
                if($oldCount<$relation->count()){
                    $res->changes['attached_'.$type]=$relatedEmployeeIds;
                }

                break;
            case 'detach':
                $relation->detach($relatedEmployeeIds);

                //todo do this properly
                $oldCount=$relation->count();
                $relation->attach($relatedEmployeeIds);
                if($oldCount>$relation->count()){
                    $res->changes['detached_'.$type]=$relatedEmployeeIds;
                }
                break;

        }


        $res->model = $employee;
        $res->model_id = $employee->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }


}
