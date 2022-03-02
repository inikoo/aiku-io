<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 16:32:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;

use App\Actions\UI\WithInertia;
use App\Http\Resources\System\UserResource;
use App\Models\Auth\User;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property User $user
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property bool $canViewEmployees
 */
class ShowUser
{
    use AsAction;
    use WithInertia;


    public function handle(User $user): User
    {
        return $user;
    }

    #[Pure] public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $this->user->tenant_id === App('currentTenant')->id && $request->user()->hasPermissionTo("account.users.view");
    }

    public function asInertia(User $user, array $attributes = []): Response
    {
        $this->set('user', $user)->fill($attributes);
        $this->validateAttributes();


        $actionIcons = [];


        /*
        $actionIcons['account.users.logbook'] =[
            'routeParameters' => $this->user->id,
            'name'            => __('History'),
            'icon'            => ['fal', 'history']
        ];
        */

        if ($this->canEdit) {
            $actionIcons['account.users.edit'] = [
                'routeParameters' => $this->user->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }

        /** @var \App\Models\HumanResources\Employee|\App\Models\HumanResources\Guest $userable */
        $userable = $this->user->userable;

        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->breadcrumbs,
                'navData'     => ['account' => 'shops', 'sectionRoot' => 'account.users.index'],

                'headerData' => [
                    'title' => $this->user->username,

                    'info' => [
                        [
                            'type' => 'group',
                            'data' => [
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => array_merge(
                                            [
                                                'type' => 'page-header',
                                                'icon' => $this->user->type_icon
                                            ],

                                        )
                                    ],
                                    [
                                        'type' => ($this->user->userable_type=='Guest' ||  $this->canViewEmployees) ? 'link' : 'text',
                                        'data' => [
                                            'slot' => $userable->name,
                                            'href' => match ($this->user->userable_type) {
                                                'Employee' => [
                                                    'route'           => 'human_resources.employees.show',
                                                    'parameters' => $this->user->userable_id
                                                ],
                                                'Guest' => [
                                                    'route'           => 'account.guests.show',
                                                    'parameters' => $this->user->userable_id
                                                ],
                                                default => null
                                            }
                                        ]
                                    ],
                                    [
                                        'type' => 'text',
                                        'data' => [
                                            'slot' =>" ({$this->user->localised_userable_type})"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'group',
                            'data' => [
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => array_merge(
                                            ['type' => 'page-header',],
                                            match ($this->user->status) {
                                                true => [
                                                    'icon'  => 'check-circle',
                                                    'class' => 'text-green-600',
                                                ],
                                                default => [
                                                    'icon'  => 'times-circle',
                                                    'class' => 'text-red-700',
                                                ]
                                            }
                                        )
                                    ],
                                    [
                                        'type' => 'text',
                                        'data' => [
                                            'slot' => $this->user->status ? __('Active') : __('Blocked')
                                        ]
                                    ]
                                ]
                            ]
                        ]


                    ],

                    'actionIcons' => $actionIcons,


                ],
                'model'      => $this->user
            ]

        );
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->set(
            'canEdit',
            ($request->user()->can('users.edit') and $this->user->userable_type != 'Tenant')
        );

        $this->set('canViewEmployees', $request->user()->can('employees.view'));

        $this->set('breadcrumbs', $this->breadcrumbs());
    }

    private function breadcrumbs(): array
    {
        return array_merge(
            (new IndexUser())->getBreadcrumbs(),
            [
                'shop' => [
                    'route'           => 'account.users.show',
                    'routeParameters' => $this->user->id,
                    'name'            => $this->user->username,
                    'current'         => true
                ],
            ]
        );
    }


}
