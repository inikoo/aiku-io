<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 02:39:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\UI\WithInertia;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property Employee $employee
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property bool $canViewUsers
 */
class ShowEmployee
{
    use AsAction;
    use WithInertia;

    public function handle(Employee $employee): Employee
    {
        return $employee;
    }

    #[Pure] public function jsonResponse(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("employees.view");
    }

    public function asInertia(Employee $employee, array $attributes = []): Response
    {
        $this->set('employee', $employee)->fill($attributes);
        $this->validateAttributes();


        $actionIcons = [];


        /*
        $actionIcons['human_resources.employees.logbook'] =[
            'routeParameters' => $this->employee->id,
            'name'            => __('History'),
            'icon'            => ['fal', 'history']
        ];
        */

        if ($this->canEdit) {
            $actionIcons['human_resources.employees.edit'] = [
                'routeParameters' => $this->employee->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'        => 'users',
                    'title'         => $this->employee->name,
                    'subTitle'      => $this->employee->nickname,
                    'titleTitle'    => __('Name'),
                    'subTitleTitle' => __('nickname'),
                    'breadcrumbs'   => $this->breadcrumbs,
                    'meta'          => [

                        match ($this->employee->state) {
                            'working' => [
                                'badge'          => 'ok',
                                'badgeClass'     => 'text-green-600',
                                'name'           => __('Working'),
                                'nameTitle'      => __('Status'),
                                'suffix'         => $this->employee->employment_start_at,
                                'suffixTitle'    => __('Start of employment'),
                                'postSuffixHtml' => ' &rarr;'
                            ],
                            'hired' => [
                                'badge'      => 'in-process',
                                'badgeClass' => 'text-green-600',
                                'name'       => __('Hired')
                            ],
                            'left' => [
                                'badge'      => 'cancelled',
                                'badgeClass' => 'text-green-600',
                                'name'       => __('Left')
                            ],
                            default => [
                                'badge'      => 'cancelled',
                                'badgeClass' => 'text-gray-700',
                                'name'       => 'Unknown'
                            ]
                        },
                        [
                            'icon'      => [
                                'fal',
                                'clipboard-user'
                            ],
                            'name'      => $this->employee->worker_number,
                            'nameTitle' => __('Worker number')
                        ],
                        [
                            'icon'      => [
                                'fal',
                                'tasks'
                            ],
                            'name'      => $this->employee->job_title ?? __('Job title not set'),
                            'nameTitle' => __('Job title'),
                            'nameClass' => !$this->employee->job_title ?? 'text-red-500',
                            'iconClass' => !$this->employee->job_title ?? 'text-red-500',
                        ],
                        [
                            'icon'      => [
                                'fal',
                                'dice-d4'
                            ],
                            'name'      => $this->employee->user ? $this->employee->user->username : __('Not an user'),
                            'nameTitle' => __('User'),
                            'nameClass' => $this->employee->user ?? 'text-gray-400 italic',
                            'iconClass' => $this->employee->user ?? 'text-gray-300 ',
                            'href'      => ($this->canViewUsers and $this->employee->user) ? [
                                'route'           => 'account.users.show',
                                'routeParameters' => $this->employee->user->id
                            ] : null


                        ],


                    ],
                    'actionIcons'   => $actionIcons,


                ],
                'model'       => $this->employee
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->set('canEdit', $request->user()->can('employees.edit'));
        $this->set('canViewUsers', $request->user()->can('users.view'));

        $this->set('breadcrumbs', $this->breadcrumbs());
    }

    private function breadcrumbs(): array
    {
        return array_merge(
            (new IndexEmployee())->getBreadcrumbs(),
            [
                'shop' => [
                    'route'           => 'human_resources.employees.show',
                    'routeParameters' => $this->employee->id,
                    'name'            => $this->employee->nickname,
                    'current'         => true
                ],
            ]
        );
    }

}
