<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 24 Aug 2021 03:12:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;


use App\Http\Controllers\Assets\CountrySelectOptionsController;
use App\Http\Controllers\Assets\LanguageSelectOptionsController;
use App\Http\Controllers\Assets\TimezoneSelectOptionsController;
use App\Http\Requests\UpdateSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TenantSettingsController extends TenantController
{


    private mixed $tenant;
    private string $module;

    public function __construct()
    {
        parent::__construct();
        $this->tenant = app('currentTenant');
        $this->module = 'system';

    }


    public function edit(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'settings' => [
                'route'   => 'tenant.settings',
                'name'    => __('Settings'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'Tenant/Settings',
            [
                'headerData'=>[
                    'title'         => __('Tenant Settings'),
                    'breadcrumbs' => $breadcrumbs,
                    'module'      => $this->module,
                    'actionIcons' => [
                        'tenant.show' => [
                            'name'            => __('Exit'),
                            'icon'            => ['fal', 'portal-exit'],
                        ]
                    ],
                ],

                'formData'=>[
                    'postURL'=>'/system/settings',
                    'blueprint' => [
                        'profile' => [
                            'title'    => __('Profile'),
                            'subtitle' => '',
                            'fields'   => [
                                'name' => [
                                    'type'  => 'text',
                                    'label' => __('Name'),
                                    'value' => $this->tenant->name
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
                                    'value'   => $this->tenant->country_id,
                                    'options' => (new CountrySelectOptionsController())()
                                ],

                                'currency_id' => [
                                    'type'    => 'select',
                                    'label'   => __('Currency'),
                                    'value'   => $this->tenant->currency_id,
                                    'options' => (new LanguageSelectOptionsController())()
                                ],
                                'language_id' => [
                                    'type'    => 'select',
                                    'label'   => __('Language'),
                                    'value'   => $this->tenant->language_id,
                                    'options' => (new LanguageSelectOptionsController())()
                                ],
                                'timezone_id' => [
                                    'type'    => 'select',
                                    'label'   => __('Timezone'),
                                    'value'   => $this->tenant->timezone_id,
                                    'options' => (new TimezoneSelectOptionsController())()
                                ],

                            ]
                        ],
                    ],
                ]




            ]
        );
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        app('currentTenant')->update($request->all());

        return Redirect::route('tenant.settings');
    }

}
