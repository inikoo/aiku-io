<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 24 Aug 2021 03:12:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\System;

use App\Actions\Account\Tenant\ShowTenant;
use App\Actions\Web\Website\IndexWebsite;
use App\Http\Controllers\Controller;

use Inertia\Inertia;
use Inertia\Response;

class TenantController extends Controller
{

    protected array $breadcrumbs = [];
    private string $module;

    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'tenant.show',
                'name'    => __('Tenant'),
                'current' => false
            ],
        ];

        $this->module = 'system';
    }


    public function show(): Response
    {
        return ShowTenant::make()->asInertia();
    }


    public function users(): Response
    {
        return ShowTenant::make()->asInertia();
    }

    public function roles(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'roles' => [
                'route'   => 'tenant.roles',
                'name'    => __('Roles'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'Tenant/Roles',
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
                'route'   => 'tenant.billing',
                'name'    => __('Billing'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'Tenant/Billing',
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
                'route'   => 'tenant.logbook',
                'name'    => __('Logbook'),
                'current' => true
            ]
        ]);


        return Inertia::render(
            'Tenant/Logbook',
            [
                'title'       => __('Logbook'),
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

}

