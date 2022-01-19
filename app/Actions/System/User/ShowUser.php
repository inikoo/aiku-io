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
use App\Models\System\User;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property User $user
 * @property array $breadcrumbs
 * @property bool $canEdit
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
        return $request->user()->hasPermissionTo("users.view");
    }

    public function asInertia(User $user, array $attributes = []): Response
    {
        $this->set('user', $user)->fill($attributes);
        $this->validateAttributes();


        $actionIcons = [];


        /*
        $actionIcons['tenant.users.logbook'] =[
            'routeParameters' => $this->user->id,
            'name'            => __('History'),
            'icon'            => ['fal', 'history']
        ];
        */

        if ($this->canEdit) {
            $actionIcons['tenant.users.edit'] = [
                'routeParameters' => $this->user->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }

        /** @var \App\Models\HumanResources\Employee|\App\Models\System\Guest $userable */
        $userable = $this->user->userable;

        return Inertia::render(
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'      => 'users',
                    'title'       => $this->user->username,
                    'breadcrumbs' => $this->breadcrumbs,
                    'meta'        => [
                        [
                            'icon' => $this->user->type_icon,
                            'name' => $userable->name." ({$this->user->localised_userable_type})",
                            'href' => match ($this->user->userable_type) {
                                'Employee'=>[
                                    'route'=>'human_resources.employees.show',
                                    'routeParameters'=>$this->user->userable_id
                                ],
                                default => null
                            }
                        ],

                        match ($this->user->status) {
                            true => [
                                'icon'      => 'check-circle',
                                'iconClass' => 'text-green-600',
                                'name'      => __('Active')
                            ],
                            default => [
                                'icon'      => 'times-circle',
                                'iconClass' => 'text-red-700',
                                'name'      => __('Blocked')
                            ]
                        }


                    ],
                    'actionIcons' => $actionIcons,


                ],
                'user'       => $this->user
            ]

        );
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'page' => 'Tenant/User',

            ]
        );
        $this->fillFromRequest($request);

        $this->set(
            'canEdit',
            ($request->user()->can('users.edit') and $this->user->userable_type != 'Tenant')
        );

        $this->set('breadcrumbs', $this->breadcrumbs());
    }

    private function breadcrumbs(): array
    {
        return array_merge(
            (new IndexUser())->getBreadcrumbs(),
            [
                'shop' => [
                    'route'           => 'tenant.users.show',
                    'routeParameters' => $this->user->id,
                    'name'            => $this->user->username,
                    'current'         => true
                ],
            ]
        );
    }


}
