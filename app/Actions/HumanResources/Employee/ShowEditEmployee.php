<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 19 Jan 2022 05:09:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\UI\WithInertia;
use App\Models\HumanResources\Employee;
use App\Models\Inventory\Warehouse;
use App\Models\Marketing\Shop;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Employee $employee
 * @property array $breadcrumbs
 */
class ShowEditEmployee
{
    use AsAction;
    use WithInertia;
    use WithJobPositionBlueprint;

    public function handle(): void
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("employees.edit");
    }

    public function asInertia(Employee $employee, array $attributes = []): Response
    {
        $this->set('employee', $employee)->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Personal information'),
            'subtitle' => '',
            'fields'   => [

                'name'          => [
                    'type'  => 'input',
                    'label' => __('Name'),
                    'value' => $this->employee->name
                ],
                'date_of_birth' => [
                    'type'  => 'date',
                    'label' => __('Date of birth'),
                    'value' => $this->employee->date_of_birth
                ],
            ]
        ];

        $blueprint[] = [
            'title'    => __('Employee data'),
            'subtitle' => '',
            'fields'   => [

                'worker_number'          => [
                    'type'  => 'input',
                    'label' => __('Worker number'),
                    'value' => $this->employee->worker_number
                ],
                'nickname'      => [
                    'type'  => 'input',
                    'label' => __('Nickname'),
                    'value' => $this->employee->nickname
                ],
                'job_title'     => [
                    'type'  => 'input',
                    'label' => __('Job title'),
                    'value' => $this->employee->job_title
                ],
                'job_positions' => [
                    'type'    => 'job-positions',
                    'label'   => __('Job positions'),
                    'value'   => $this->getJobPositionValue(),
                    'options' => [
                        'blueprint' => $this->getJobPositionBlueprint()
                    ]
                ],
            ]
        ];


        return Inertia::render(
            'edit-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->employee),
                'navData'     => ['module' => 'human_resources', 'sectionRoot' => 'human_resources.employees.index'],
                'headerData'  => [
                    'title' => __('Editing').': '.$this->employee->name,

                    'actionIcons' => [

                        [
                            'route'           => 'human_resources.employees.show',
                            'routeParameters' => $this->employee->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit'],
                        ],
                    ],


                ],
                'employee'    => $this->employee,
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/human_resources/employees/{$this->employee->id}",
                    ]

                ],
            ]

        );
    }

    protected function getJobPositionValue(): array
    {
        $currentPositions = $this->employee->jobPositions()->pluck('job_positions.id', 'slug')->all();


        $value['scopes'] = [];

        foreach (
            config("app_type.".app('currentTenant')->appType->code.".job_positions.blueprint")
            as $i => $foo
        ) {
            if (!empty($foo['scope'])) {
                $value['scopes'][$i] = Arr::get($this->employee->job_position_scopes, $i);
            }
        }


        $positions = [];
        foreach (
            config("app_type.".app('currentTenant')->appType->code.".job_positions.positions")
            as $i => $foo
        ) {
            $positions[$i] = Arr::exists($currentPositions, $i);
        }


        foreach (
            config("app_type.".app('currentTenant')->appType->code.".job_positions.wrappers")
            as $i => $foo
        ) {
            $positions[$i] = Arr::hasAny($currentPositions, $foo);
        }


        $value['positions'] = $positions;

        return $value;
    }




    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Employee $employee): array
    {
        return (new ShowEmployee())->getBreadcrumbs($employee);
    }


}
