<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:42:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Auth\User;

use App\Actions\UI\WithInertia;
use App\Models\Auth\User;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

use function __;
use function app;


/**
 * @property User $user
 * @property array $breadcrumbs
 */
class ShowEditUser
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->user->userable_type == 'Tenant') {
            return false;
        }

        return $this->user->tenant_id === App('currentTenant')->id && $request->user()->hasPermissionTo("account.users.edit");
    }

    public function asInertia(User $user, array $attributes = []): Response
    {
        $this->set('user', $user)->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Status'),
            'subtitle' => '',
            'fields'   => [

                'status' => [
                    'type'  => 'toggleWithIcon',
                    'label' => __('Status'),
                    'value' => $this->user->status
                ],
            ]
        ];


        return Inertia::render(
            'edit-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->user),
                'navData'     => ['module' => 'account', 'sectionRoot' => 'account.users.index'],

                'headerData' => [
                    'title' => __('Editing').': '.$this->user->username,

                    'actionIcons' => [

                        [
                            'route'           => 'account.users.show',
                            'routeParameters' => $this->user->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit'],

                        ],
                    ],


                ],

                'formData' => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/account/users/{$this->user->id}",
                    ]

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(User $user): array
    {
        return (new ShowUser())->getBreadcrumbs($user);
    }


}
