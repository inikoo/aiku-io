<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 24 Sep 2021 12:00:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\HumanResources\Employee;
use App\Rules\Phone;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployee
{
    use AsAction;
    use WithUpdate;

    public function handle(Employee $employee, array $contactData, array $employeeData): ActionResult
    {
        $res = new ActionResult();

        $employee->contact()->update($contactData);

        $res->changes = array_merge($res->changes, $employee->contact->getChanges());

        $employee->update($employeeData);

        $employee->update(Arr::except($employeeData, ['data']));
        $employee->update($this->extractJson($employeeData));

        $res->changes = array_merge($res->changes, $employee->getChanges());

        $res->model = $employee;

        $res->model_id = $employee->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit');
    }

    public function rules(): array
    {
        return [
            'name'   => 'sometimes|required|string',
            'email'  => 'sometimes|email',
            'phone'  => ['string', new Phone()],
            'status' => [
                'sometimes',
                'required',
                Rule::in(['working', 'ex-employee', 'hired']),
            ]
        ];
    }

    public function asController(Employee $employee, ActionRequest $request): ActionResultResource
    {
        return new ActionResultResource(
            $this->handle(
                $employee,
                $request->only('name', 'email', 'phone'),
                $request->only('nickname')
            )
        );
    }

}
