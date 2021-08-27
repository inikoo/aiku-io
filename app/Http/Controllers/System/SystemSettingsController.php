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

class SystemSettingsController extends SystemController
{


    private mixed $tenant;

    public function __construct()
    {
        parent::__construct();
        $this->tenant = app('currentTenant');
    }


    public function edit(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'settings' => [
                'route'   => 'system.settings',
                'name'    => __('Settings'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'System/Settings',
            [
                'title'         => __('System Settings'),
                'formBlueprint' => [
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


                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        app('currentTenant')->update($request->all());

        return Redirect::route('system.settings');
    }

}

