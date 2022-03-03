<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 02:39:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\HumanResources\ShowHumanResourcesDashboard;
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
                'icon'            => ['fal', 'edit'],
                'primary'         => true,
            ];
        }


        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->employee),
                'navData'     => ['module' => 'human_resources', 'sectionRoot' => 'human_resources.employees.index'],

                'headerData' => [
                    'module'        => 'users',
                    'title'         => $this->employee->name,
                    'subTitle'      => $this->employee->nickname,
                    'titleTitle'    => __('Name'),
                    'subTitleTitle' => __('nickname'),

                    'info' => [
                        [
                            'type' => 'badge',
                            'data' => match ($this->employee->state) {
                                'working' => [
                                    'type'  => 'ok',
                                    'title' => __('Status'),
                                    'slot'  =>  __('Working')
                                ],
                                'hired' => [
                                    'type'      => 'in-process',
                                    'badgeClass' => 'text-green-600',
                                    'slot'       => __('Hired')
                                ],
                                'left' => [
                                    'type'      => 'cancelled',
                                    'class' => 'text-green-600',
                                    'slot'       => __('Left')
                                ],
                                default => [
                                    'type'      => 'cancelled',
                                    'class' => 'text-gray-700',
                                    'slot'       => 'Unknown'
                                ]
                            }
                        ],
                        [
                            'type' => 'group',
                            'data' => [
                                'title'      => __('Worker number'),
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => [
                                            'icon' => ['fal', 'clipboard-user'],
                                            'type' => 'page-header'
                                        ]
                                    ],
                                    [
                                        'type' => 'text',
                                        'data' => [
                                            'slot' => $this->employee->worker_number,

                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'group',
                            'data' => [
                                'title'      => __('Job title'),
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => [
                                            'icon'  => ['fal', 'tasks'],
                                            'type'  => 'page-header',
                                            'class' => !$this->employee->job_title ?? 'text-red-500'

                                        ]
                                    ],
                                    [
                                        'type' => 'text',
                                        'data' => [
                                            'slot'  => $this->employee->job_title ?? __('Job title not set'),
                                            'class' => !$this->employee->job_title ?? 'text-red-500'

                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'group',
                            'data' => [
                                'title'      => __('User'),
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => [
                                            'icon'  => ['fal', 'dice-d4'],
                                            'type'  => 'page-header',
                                            'class' => $this->employee->user ?? 'text-gray-300'

                                        ]
                                    ],

                                    $this->employee->user
                                        ?
                                        [
                                            'type' => $this->canViewUsers?'link':'text',
                                            'data' => [
                                                'href'      =>  [
                                                    'route'           => 'account.users.show',
                                                    'parameters' => $this->employee->user->id
                                                ],
                                                'slot' => $this->employee->user->username

                                            ]
                                        ]
                                        :
                                        [
                                            'type' => 'text',
                                            'data' => [
                                                'slot'  => __('Not an user'),
                                                'class' => 'text-gray-300 italic'

                                            ]
                                        ],


                                ]
                            ]
                        ]

                    ],



                    'actionIcons' => $actionIcons,


                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->set('canEdit', $request->user()->can('employees.edit'));
        $this->set('canViewUsers', $request->user()->can('users.view'));

    }

    public function getBreadcrumbs(Employee $employee): array
    {
        return array_merge(
            (new ShowHumanResourcesDashboard())->getBreadcrumbs(),
            [
                'human_resources.employees.show' => [
                    'route'           => 'human_resources.employees.show',
                    'routeParameters' => $employee->id,
                    'name'            => $employee->nickname,
                    'index'           => [
                        'route'   => 'human_resources.employees.index',
                        'overlay' => __('Employees index')
                    ],
                    'modelLabel'      => [
                        'label' => __('employee')
                    ],
                ],
            ]
        );
    }

}
