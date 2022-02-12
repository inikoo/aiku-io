<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 19 Jan 2022 23:44:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Account;

use App\Actions\UI\WithInertia;
use App\Http\Resources\Account\TenantResource;
use App\Models\Account\Tenant;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property string $module
 * @property string $title
 * @property Tenant $account
 * @property array $breadcrumbs
 */
class ShowAccount
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }

    #[Pure] public function jsonResponse(Tenant $tenant): TenantResource
    {
        return new TenantResource($tenant);
    }


    public function asInertia(array $attributes = []): Response
    {


        $this->set('account',app('currentTenant'))->fill($attributes);
        $this->validateAttributes();

        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->breadcrumbs,
                'navData' => ['module' => 'account'],

                'headerData' => [
                    'title'       => $this->title,
                    'meta'        => [
                        [
                            'icon' => ['fal','user-circle'],
                            'name' => App('currentTenant')->stats->number_users_status_active??0,
                            'href' =>[
                                'route'=>'account.users.index',
                            ]
                        ],
                        [
                            'icon' => ['fal','user-alien'],
                            'name' => App('currentTenant')->stats->number_guests_status_active??0,
                            'href' =>[
                                'route'=>'account.guests.index',
                            ]
                        ],
                    ],

                    'actionIcons' => [
                        /*
                        'account.logbook'  => [
                            'name' => 'History',
                            'icon' => ['fal', 'history']
                        ],
                        */
                        'account.edit' => [
                            'name' => 'Settings',
                            'icon' => ['fal', 'sliders-h-square']
                        ],

                    ]
                ]
            ]
        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => $this->account->name,

            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        return [
            'tenant' => [
                'route'   => 'account.show',
                'name'    => __('Account'),
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs();
    }

}
