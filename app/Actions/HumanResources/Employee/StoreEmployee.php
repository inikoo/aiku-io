<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 02:39:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Models\HumanResources\Workplace;
use App\Models\Utils\ActionResult;
use App\Models\HumanResources\Employee;
use App\Rules\Phone;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreEmployee
{
    use AsAction;


    public function handle(?Workplace $workplace, array $contactData, array $employeeData): ActionResult
    {
        $res = new ActionResult();


        // no normal data
        $employeeData=array_merge($employeeData,Arr::only($contactData,['name','email','phone']));

        /** @var Employee $employee */
        if ($workplace) {

            $employee = $workplace->employees()->create($employeeData);
        } else {
            $employee = Employee::create($employeeData);
        }


        $employee->contact()->create($contactData);
        $employee->save();

        $res->model    = $employee;
        $res->model_id = $employee->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('employee:store');
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string',
            'email'  => 'email',
            'phone'  => ['string', new Phone()],
            'status' => [
                'required',
                Rule::in(['working', 'ex-employee', 'hired']),
            ]
        ];
    }

    public function asController(ActionRequest $request): ActionResult
    {
        return $this->handle(
            workplace:    null,
            contactData:  $request->only('name', 'email', 'phone'),
            employeeData: $request->only('status')
        );
    }
}
