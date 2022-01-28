<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 17 Jan 2022 23:34:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;

use App\Actions\UI\WithInertia;
use App\Models\System\User;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


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
        return $request->user()->hasPermissionTo("account.users.edit");
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
                    'type'    => 'toggleWithIcon',
                    'label'   => __('Status'),
                    'value'   => $this->user->status
                ],
            ]
        ];

        return Inertia::render(
            'Common/EditModel',
            [
                'headerData' => [
                    'module'      => 'users',
                    'title'       => __('Editing').': '.$this->user->username,
                    'breadcrumbs' => $this->breadcrumbs,

                    'actionIcons' => [

                        'account.users.show' => [
                            'routeParameters' => $this->user->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],



                ],

                'formData'    => [
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
        $this->set('breadcrumbs', $this->breadcrumbs());
    }

    private function breadcrumbs(): array
    {
        return array_merge(
            (new IndexUser())->getBreadcrumbs(),
            [
                'user' => [
                    'route'           => 'account.users.edit',
                    'routeParameters' => $this->user->id,
                    'name'            => $this->user->username,
                    'suffix'          => '('.__('editing').')',
                    'current'         => true
                ],

            ]
        );
    }


}