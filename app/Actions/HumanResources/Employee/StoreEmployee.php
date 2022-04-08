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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;


class StoreEmployee
{
    use AsAction;
    use WithAttributes;


    public function handle(?Workplace $workplace, array $employeeData): ActionResult
    {
        $res = new ActionResult();


        /** @var Employee $employee */
        if ($workplace) {

            $employee = $workplace->employees()->create($employeeData);
        } else {
            $employee = Employee::create($employeeData);
        }


        $employee->save();

        $res->model    = $employee;
        $res->model_id = $employee->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('employee:store') ||
            $request->user()->hasPermissionTo("employees.edit");

    }




    public function rules(): array
    {
        return [
            'name'                     => 'required|string',
            'email'                    => 'email',
            'phone'                    => 'phone:AUTO',
            'identity_document_number' => 'required|string',
            'date_of_birth'            => 'nullable|date|before_or_equal:today',
            'address'                  => 'nullable|string',
            'emergency_contact'        => 'nullable|string',
            'nickname'                 => 'required|string',
            'worker_number'            => 'required|string',


        ];
    }



    public function asController(ActionRequest $request): ActionResult
    {
        $request->validate();
        return $this->handle(
            workplace:    null,
            employeeData: $request->only('status','name', 'email', 'phone')
        );
    }

    public function asInertia(Request $request):RedirectResponse
    {

        $this->fillFromRequest($request);
        $this->validateAttributes();

        $employeeData=$request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'date_of_birth',
            'nickname',
            'worker_number',
            'emergency_contact',
        );

        $res=$this->handle(
            null,
            $employeeData

        );


        return Redirect::route('human_resources.employees.show', $res->model_id);


    }

}
