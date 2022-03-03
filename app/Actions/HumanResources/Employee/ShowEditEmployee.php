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

    public function handle()
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
            'title'    => __('Employee data'),
            'subtitle' => '',
            'fields'   => [

                'name' => [
                    'type'    => 'input',
                    'label'   => __('Worker number'),
                    'value'   => $this->employee->worker_number
                ],
                'nickname' => [
                    'type'    => 'input',
                    'label'   => __('Nickname'),
                    'value'   => $this->employee->nickname
                ],
            ]
        ];

        $blueprint[] = [
            'title'    => __('Personal information'),
            'subtitle' => '',
            'fields'   => [

                'name' => [
                    'type'    => 'input',
                    'label'   => __('Name'),
                    'value'   => $this->employee->name
                ],
            ]
        ];

        return Inertia::render(
            'edit-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->employee),
                'navData'     => ['module' => 'human_resources', 'sectionRoot' => 'human_resources.employees.index'],
                'headerData' => [
                    'title'       => __('Editing').': '.$this->employee->name,

                    'actionIcons' => [

                        'human_resources.employees.show' => [
                            'routeParameters' => $this->employee->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],



                ],
                'employee'       => $this->employee,
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/human_resources/employees/{$this->employee->id}",
                    ]

                ],
            ]

        );
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
