<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 24 Aug 2021 03:12:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;

use Inertia\Inertia;
use Inertia\Response;

class SystemController extends Controller
{

    protected array $breadcrumbs = [];

    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'system.index',
                'name'    => __('System'),
                'current' => false
            ],
        ];
    }


    public function index(): Response
    {
        return Inertia::render(
            'System/System',
            [
                'title'       => __('System'),
                'breadcrumbs' => data_set($this->breadcrumbs, "index.current", true),
                'actionIcons' => [
                    'system.logbook'  => [
                        'name' => 'History',
                        'icon' => ['fal', 'history']
                    ],
                    'system.settings' => [
                        'name' => 'Settings',
                        'icon' => ['fal', 'sliders-h-square']
                    ],
                ]
            ]
        );
    }



    public function users(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'users' => [
                'route'   => 'system.users',
                'name'    => __('Users'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'System/Users',
            [
                'title'       => __('Users'),
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    public function roles(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'roles' => [
                'route'   => 'system.roles',
                'name'    => __('Roles'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'System/Roles',
            [
                'title'       => __('Roles'),
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    public function billing(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'billing' => [
                'route'   => 'system.billing',
                'name'    => __('Billing'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'System/Billing',
            [
                'title'       => __('Billing'),
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    public function logbook(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'logbook' => [
                'route'   => 'system.logbook',
                'name'    => __('Logbook'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'System/Logbook',
            [
                'title'       => __('Logbook'),
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

}

