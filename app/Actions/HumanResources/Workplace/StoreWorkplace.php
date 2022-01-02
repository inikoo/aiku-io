<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 02 Jan 2022 00:48:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Workplace;

use App\Models\Account\Tenant;
use App\Models\HumanResources\Employee;
use App\Models\System\Guest;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreWorkplace
{
    use AsAction;


    public function handle(Employee|Guest|Tenant $owner, array $workplaceData): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\HumanResources\Workplace $workplace */

        switch (class_basename($owner)) {
            case 'Tenant':
                $workplace = $owner->workplaces()->create($workplaceData);
                break;
            case 'Employee':
                $workplace = $owner->homeOffice()->create($workplaceData);
                break;
            case 'Guest':
                $workplace = $owner->ownOffice()->create($workplaceData);
                break;
            default:
                $res->status = 'error';

                return $res;
        }


        $workplace->save();

        $res->model    = $workplace;
        $res->model_id = $workplace->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }

    /*

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('employee:store');
    }

    //todo rule for address
    public function rules(): array
    {
        return [
            'name'   => 'required|string',
            'timezone_id'  => 'required|aiku.timezones,id',
            'type' => [
                'required',
                Rule::in(['hq','satellite','branch','home','workplace']),
            ]
        ];
    }

    public function asController(Employee $employee, ActionRequest $request): ActionResult
    {
        return $this->handle(
            $request->only('day'),
        );

        if ($actionResult->status == 'error') {
            return response()->json([
                                        'message' => 'Image can not be added',
                                        'errors'  => $actionResult->errors,
                                    ], 422);
        } else {
            return new ActionResultResource($actionResult);
        }
    }
    */
}
