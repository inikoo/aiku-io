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


        $contact = $employee->contact;

        $contact->update(Arr::except($contactData, ['data']));
        $contact->update($this->extractJson($contactData));

        $res->changes = array_merge($res->changes, $contact->getChanges());
        $employee->update(Arr::except($employeeData, ['data', 'salary', 'working_hours']));
        $employee->update($this->extractJson($employeeData, ['data', 'salary', 'working_hours']));

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
            'name'                     => 'sometimes|required|string',
            'email'                    => 'sometimes|email',
            'phone'                    => ['string', new Phone()],
            'identity_document_number' => 'sometimes|required|string',
            'date_of_birth'            => 'sometimes|nullable|date|before_or_equal:today',
            'address'                  => 'sometimes|nullable|string',
            'emergency_contact'        => 'sometimes|nullable|string',
            'nickname'                 => 'sometimes|required|string',
            'worker_number'            => 'sometimes|required|string',
            'salary'                   => 'sometimes|required|array',
            'working_hours'            => 'sometimes|required|array',

            'status' => [
                'sometimes',
                'required',
                Rule::in(['working', 'ex-employee', 'hired']),
            ]
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->exists('salary')) {
            $request->merge(
                [
                    'salary' => json_decode($request->get('salary'), true),

                ]

            );
        }
        if ($request->exists('working_hours')) {
            $request->merge(
                [
                    'working_hours' => json_decode($request->get('working_hours'), true)

                ]

            );
        }
    }

    public function asController(Employee $employee, ActionRequest $request): ActionResultResource
    {
        $contact = $request->only('name', 'email', 'phone', 'identity_document_number', 'date_of_birth');
        if ($contactData = $request->only('address')) {
            $contact['data'] = $contactData;
        }


        return new ActionResultResource(
            $this->handle(
                $employee,
                $contact,
                $request->only(
                    'nickname',
                    'worker_number',
                    'emergency_contact',
                    'salary',
                    'job_title',
                    'working_hours'
                )
            )
        );
    }

}
