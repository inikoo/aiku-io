<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 00:16:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Account;

use App\Actions\Assets\Country\IndexCountry;
use App\Actions\Assets\Currency\IndexCurrency;
use App\Actions\Assets\Language\IndexLanguage;
use App\Actions\Assets\Timezone\IndexTimezone;
use App\Actions\UI\Localisation\GetUITranslations;
use App\Actions\UI\WithInertia;
use App\Http\Resources\Account\TenantResource;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\App;
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
class ShowEditAccount
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
        $this->set('account', app('currentTenant'))->fill($attributes);
        $this->validateAttributes();


        return Inertia::render(
            'edit-model',
            [
                'translations' => GetUITranslations::run(),
                'language'     => App::currentLocale(),
                'tenant'       => app('currentTenant')->only('name', 'code'),
                'breadcrumbs' => $this->breadcrumbs,

                'headerData'   => [

                    'title'       => __('Account settings', ['name' => $this->account->name]),
                    'actionIcons' => [

                        'account.show' => [
                            'name' => __('Exit edit'),
                            'icon' => ['fal', 'portal-exit']
                        ],

                    ]
                ],
                'formData'     => [
                    'blueprint' => [
                        'profile' => [
                            'title'    => __('Profile'),
                            'subtitle' => '',
                            'fields'   => [
                                'name' => [
                                    'type'  => 'text',
                                    'label' => __('Name'),
                                    'value' => $this->account->name
                                ]
                            ]
                        ],
                        'locale'  => [
                            'title'    => __('Localisation'),
                            'subtitle' => __('Default values used throughout the system'),
                            'fields'   => [
                                'country_id' => [
                                    'type'    => 'select',
                                    'label'   => __('Country'),
                                    'value'   => $this->account->country_id,
                                    'options' => IndexCountry::make()->asSelectOptions()
                                ],

                                'currency_id' => [
                                    'type'    => 'select',
                                    'label'   => __('Currency'),
                                    'value'   => $this->account->currency_id,
                                    'options' => IndexCurrency::make()->asSelectOptions()
                                ],
                                'language_id' => [
                                    'type'    => 'select',
                                    'label'   => __('Language'),
                                    'value'   => $this->account->language_id,
                                    'options' => IndexLanguage::make()->asSelectOptions()
                                ],
                                'timezone_id' => [
                                    'type'    => 'select',
                                    'label'   => __('Timezone'),
                                    'value'   => $this->account->timezone_id,
                                    'options' => IndexTimezone::make()->asSelectOptions()
                                ],

                            ]
                        ],
                    ],
                    'args'      => [
                        'postURL' => "/account",
                    ]

                ],
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
                'name'    => __('Account').' ['.$this->account->code.']',
                'suffix'  => '('.__('editing').')',
                'current' => false
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs();
    }

}
