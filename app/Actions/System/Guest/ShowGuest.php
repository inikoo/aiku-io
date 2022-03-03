<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:19:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Actions\Account\Tenant\ShowTenant;
use App\Actions\UI\WithInertia;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Guest;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property Guest $guest
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property bool $canViewUsers
 */
class ShowGuest
{
    use AsAction;
    use WithInertia;

    public function handle(Guest $guest): Guest
    {
        return $guest;
    }

    #[Pure] public function jsonResponse(Guest $guest): EmployeeResource
    {
        return new EmployeeResource($guest);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("account.view");
    }

    public function asInertia(Guest $guest, array $attributes = []): Response
    {
        $this->set('guest', $guest)->fill($attributes);
        $this->validateAttributes();


        $actionIcons = [];


        if ($this->canEdit) {
            $actionIcons['account.guests.edit'] = [
                'routeParameters' => $this->guest->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }

        return Inertia::render(
            'show-model',
            [
                'breadcrumbs'   => $this->getBreadcrumbs($guest),
                'navData'     => ['module' => 'account', 'sectionRoot' => 'account.guests.index'],

                'headerData' => [
                    'title'         => $this->guest->name,
                    'titleTitle'    => __('Name'),



                    'info' => [

                        [
                            'type' => 'group',
                            'data' => [
                                'components' => [
                                    [
                                        'type' => 'icon',
                                        'data' => array_merge(
                                            ['type' => 'page-header',],
                                            match ($this->guest->status) {
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
                                            'slot' => $this->guest->status ? __('Active') : __('Collaboration finished')
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
                                            'class' => $this->guest->user ?? 'text-gray-300'

                                        ]
                                    ],

                                    $this->guest->user
                                        ?
                                        [
                                            'type' => $this->canViewUsers?'link':'text',
                                            'data' => [
                                                'href'      =>  [
                                                    'route'           => 'account.users.show',
                                                    'parameters' => $this->guest->user->id
                                                ],
                                                'slot' => $this->guest->user->username

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



                    'actionIcons'   => $actionIcons,


                ],
                'model'      => $this->guest
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->set('canEdit', $request->user()->can('account.edit'));
        $this->set('canViewUsers', $request->user()->can('account.view'));

    }

    public function getBreadcrumbs(Guest $guest): array
    {
        return array_merge(
            (new ShowTenant())->getBreadcrumbs(),
            [

                'account.guests.show' => [
                    'route'           => 'account.guests.show',
                    'routeParameters' => $guest->id,
                    'index'=>[
                        'route'   => 'account.guests.index',
                        'overlay' => __('Guest index')
                    ],
                    'modelLabel'=>[
                        'label'=>__('guest')
                    ],
                    'name'            => $guest->nickname,

                ],


            ]
        );
    }
}
