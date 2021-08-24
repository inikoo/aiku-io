<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 24 Aug 2021 03:12:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;


use App\Http\Controllers\Assets\CountrySelectOptionsController;
use App\Http\Requests\StoreSettingsRequest;
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


    public function settings(): Response
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
                            'country' => [
                                'type'    => 'select',
                                'label'   => __('Country'),
                                'value'   => $this->tenant->country,
                                'options' => (new CountrySelectOptionsController())()
                            ],
                            /*
                            'currency' => [
                                'type'    => 'select',
                                'label'   => __('Currency'),
                                'value'   => $this->tenant->country,
                                'options' => (new CurrencySelectOptionsController())()
                            ]
                            */
                        ]
                    ],
                ],


                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    public function store(StoreSettingsRequest $request): RedirectResponse
    {
        app('currentTenant')->update($request->all());

        return Redirect::route('system.settings');
    }

}

