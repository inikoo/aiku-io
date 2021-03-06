<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Feb 2022 00:23:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing;


use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowMarketingDashboard
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.view");
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
                'navData'     => ['module' => 'marketing', 'metaSection' => 'shops', 'sectionRoot' => 'marketing.dashboard'],
                'headerData'  => [
                    'title' => __('Marketing'),
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
                'route' => 'marketing.dashboard',
                'name'  => __('Marketing'),
            ]
        ];
    }


}
