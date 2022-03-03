<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 27 Jan 2022 22:32:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Actions\UI\WithInertia;
use App\Models\HumanResources\Guest;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property \App\Models\HumanResources\Guest $guest
 * @property array $breadcrumbs
 */
class ShowEditGuest
{
    use AsAction;
    use WithInertia;

    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("account.edit");
    }

    public function asInertia(Guest $guest, array $attributes = []): Response
    {
        $this->set('guest', $guest)->fill($attributes);
        $this->validateAttributes();

        $blueprint = [];

        $blueprint[] = [
            'title'    => __('Guest/User data'),
            'subtitle' => '',
            'fields'   => [
                'status' => [
                    'type'    => 'toggleWithIcon',
                    'label'   => __('Status'),
                    'value'   => $this->guest->status
                ],
                'username' => [
                    'type'    => 'input',
                    'label'   => __('Username'),
                    'value'   => $this->guest->user->username
                ],
                'password' => [
                    'type'    => 'input',
                    'label'   => __('Password'),
                    'placeholder'=>__('Password'),
                    'value'   => ''
                ],
            ]
        ];

        $blueprint[] = [
            'title'    => __('Contact information'),
            'subtitle' => '',
            'fields'   => [
                'nickname' => [
                    'type'    => 'input',
                    'label'   => __('Nickname'),
                    'value'   => $this->guest->nickname
                ],
                'name' => [
                    'type'    => 'input',
                    'label'   => __('Name'),
                    'value'   => $this->guest->name
                ],
                'email' => [
                    'type'    => 'input',
                    'label'   => __('Email'),
                    'value'   => $this->guest->email
                ],
                'phone' => [
                    'type'    => 'phone',
                    'label'   => __('Phone'),
                    'value'   => $this->guest->phone
                ],
            ]
        ];

        return Inertia::render(
            'edit-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->guest),
                'navData'     => ['module' => 'account', 'sectionRoot' => 'account.guests.index'],
                'headerData' => [
                    'title'       => __('Editing').': '.$this->guest->name,
                    'actionIcons' => [

                        'account.guests.show' => [
                            'routeParameters' => $this->guest->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit']
                        ],
                    ],
                ],
                'guest'       => $this->guest,
                'formData'    => [
                    'blueprint' => $blueprint,
                    'args'      => [
                        'postURL' => "/account/guests/{$this->guest->id}",
                    ]

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Guest $guest): array
    {
        return (new ShowGuest())->getBreadcrumbs($guest);
    }


}
