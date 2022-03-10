<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 24 Sep 2021 12:00:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\HumanResources\JobPosition;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\HumanResources\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateEmployee
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(Employee $employee, array $employeeData): ActionResult
    {
        $res = new ActionResult();


        $employee->update(
            Arr::except($employeeData, [
                'data',
                'salary',
                'working_hours',
                'employee_relationships',
                'job_positions'

            ])
        );
        $employee->update($this->extractJson($employeeData, ['data', 'salary', 'working_hours']));

        $res->changes = $employee->getChanges();

        if (Arr::exists($employeeData, 'job_positions')) {
            $res          = UpdateEmployeeJobPositions::run(
                employee:  $employee,
                operation: $employeeData['job_positions']['operation'],
                ids:       $employeeData['job_positions']['ids'],

            );
            $res->changes = array_merge($res->changes, $res->changes);
        }

        if (Arr::exists($employeeData, 'employee_relationships')) {
            $res          = UpdateEmployeeRelationships::run(
                employee:           $employee,
                type:               $employeeData['employee_relationships']['type'],
                operation:          $employeeData['employee_relationships']['operation'],
                relatedEmployeeIds: $employeeData['employee_relationships']['ids'],

            );
            $res->changes = array_merge($res->changes, $res->changes);
        }


        $res->model = $employee;

        $res->model_id = $employee->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit') || $request->user()->hasPermissionTo("employees.edit");
    }

    public function rules(): array
    {
        return [
            'name'                     => 'sometimes|required|string',
            'email'                    => 'sometimes|email',
            'phone'                    => 'sometimes|phone:AUTO',
            'identity_document_number' => 'sometimes|required|string',
            'date_of_birth'            => 'sometimes|nullable|date|before_or_equal:today',
            'address'                  => 'sometimes|nullable|string',
            'emergency_contact'        => 'sometimes|nullable|string',
            'nickname'                 => 'sometimes|required|string',
            'worker_number'            => 'sometimes|required|string',
            'salary'                   => 'sometimes|required|array',
            'working_hours'            => 'sometimes|required|array',
            'employee_relationships'   => 'sometimes|required|array:type,operation,ids',
            'job_position_slugs'       => 'sometimes|required|array:operation,slugs',

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
        if ($request->exists('employee_relationships')) {
            $request->merge(
                [
                    'employee_relationships' => json_decode($request->get('employee_relationships'), true)
                ]

            );
        }

        if ($request->exists('job_position_slugs')) {
            $request->merge(
                [
                    'job_position_slugs' => json_decode($request->get('job_position_slugs'), true)
                ]

            );
        }
    }


    public function afterValidator(Employee $employee, Validator $validator, ActionRequest $request): void
    {
        if ($request->exists('employee_relationships')) {
            $employee_relationships = json_decode($request->get('employee_relationships'), true);

            foreach ($employee_relationships['ids'] as $id) {
                $relatedEmployee = Employee::find($id);
                if ($relatedEmployee and $employee->id == $relatedEmployee->id) {
                    $validator->errors()->add('employee_relationships', 'Related employee same as employee.');
                } else {
                    $validator->errors()->add('employee_relationships', 'Related employee not found.');
                }
            }
        }

        if ($request->exists('job_position_slugs')) {
            $jobPositions = [];

            $job_position_slugs = json_decode($request->get('job_position_slugs'), true);

            foreach ($job_position_slugs['slugs'] as $slug) {
                $jobPosition = JobPosition::firstWhere('slug', $slug);
                if ($jobPosition) {
                    $jobPositions[] = $jobPosition->id;
                } else {
                    $validator->errors()->add('job_positions', 'Wrong job position slug.');
                }
            }

            $request->merge(
                [
                    'job_positions' =>
                        [
                            'operation' => $job_position_slugs['operation'],
                            'ids'       => $jobPositions
                        ]
                ]
            );
        }
    }


    public function asController(Employee $employee, ActionRequest $request): ActionResultResource
    {
        $request->validate();

        $modelData = $request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'date_of_birth',
            'nickname',
            'worker_number',
            'emergency_contact',
            'salary',
            'job_title',
            'working_hours',
            'employee_relationships',
            'job_positions'

        );
        $data      = $request->only('address');
        if ($data) {
            $modelData['data'] = $data;
        }


        return new ActionResultResource(
            $this->handle(
                $employee,
                $modelData
            )
        );
    }

    public function asInertia(Employee $employee, Request $request): RedirectResponse
    {
        $this->set('employee', $employee);
        $this->fillFromRequest($request);
        $this->validateAttributes();


        $modelData = $request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'date_of_birth',
            'nickname',
            'worker_number',
            'emergency_contact',
            'salary',
            'job_title',
            'working_hours',
            'employee_relationships',
            'job_positions'

        );
        $data      = $request->only('address');
        if ($data) {
            $modelData['data'] = $data;
        }

        $this->handle(
            $employee,
            $modelData

        );

        return Redirect::route('human_resources.employees.edit', $employee->id);
    }

}
