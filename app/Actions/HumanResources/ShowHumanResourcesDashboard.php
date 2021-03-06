<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Feb 2022 14:24:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources;


use App\Actions\UI\WithInertia;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowHumanResourcesDashboard
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("employees.view");
    }


    public function asInertia(array $attributes = []): Response
    {
        $this->fill($attributes);

        $this->validateAttributes();

        $page = 'show-dashboard';
        if (Employee::count() == 0) {
            $page       = 'empty-state';
            $emptyState = [
                'title'    => __('No employees'),
                'subtitle' => __('Get started by creating a new employee.'),
                'action'   => __('New employee'),
                'route'    => 'human_resources.employees.create'
            ];
        }


        return Inertia::render(
            $page,
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => ['module' => 'human_resources'],
                'headerData'  => [
                    'title' => __('Human resources'),
                    'info'  => [
                        [
                            'type' => 'group',
                            'data' => [
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => [
                                            'icon' => ['fal', 'user-hard-hat'],
                                            'type' => 'page-header'
                                        ]
                                    ],
                                    [
                                        'type' => 'number',
                                        'data' => [
                                            'slot' => App('currentTenant')->stats->number_employees_state_working
                                        ]
                                    ],
                                    [
                                        'type' => 'link',
                                        'data' => [
                                            'slot'  => ' '.trans_choice(__('employee'), App('currentTenant')->stats->number_employees_state_working),
                                            'class' => 'pr-1',
                                            'href'  => [
                                                'route' => 'human_resources.employees.index',
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],

                    ],

                ],
                'emptyState'  => $emptyState ?? null
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return [
            'human_resources.dashboard' => [
                'route' => 'human_resources.dashboard',
                'name'  => __('Human resources'),
            ]
        ];
    }


}
