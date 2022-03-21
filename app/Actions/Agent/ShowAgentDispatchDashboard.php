<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 20 Mar 2022 01:45:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Agent;


use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowAgentDispatchDashboard
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return true;
        return $request->user()->hasPermissionTo("agent_dispatch.stores")  ||
            $request->user()->hasPermissionTo("agent_dispatch.clients")  ||
            $request->user()->hasPermissionTo("agent_dispatch.orders") ;

    }

    public function asInertia(array $attributes = []): Response
    {
        $this->fill($attributes);
        $this->validateAttributes();


        $tenant = app('currentTenant');

        return Inertia::render(
            'marketing-dashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => ['module' => 'agent_dispatch', 'metaSection' => 'stores', 'sectionRoot' => 'agent_dispatch.dashboard'],
                'headerData'  => [
                    'title' => __('Dispatch'),
                ],
                'stats'       => [
                    'models' => [
                        [
                            'name' => __('Total customers'),
                            'stat' => $tenant->marketingStats->number_customers,
                            'href' => [
                                'route' => 'marketing.customers.index'
                            ]
                        ],
                        [
                            'name' => __('Total orders'),
                            'stat' => $tenant->marketingStats->number_orders,
                            'href' => [
                                'route' => 'marketing.orders.index'
                            ]
                        ],
                    ]
                ]


            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return [
            'marketing.dashboard' => [
                'route' => 'agent_dispatch.dashboard',
                'name'  => __('Dispatch'),
            ]
        ];
    }


}
