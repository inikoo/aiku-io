<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 16 Jan 2022 12:31:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory;


use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowInventoryDashboard
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("inventory.stocks.view");
    }


    public function asInertia(array $attributes = []): Response
    {
        $this->fill($attributes);

        $this->validateAttributes();


        return Inertia::render(
            'show-dashboard',
            [


                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData'     => [
                    'module' => 'account',
                    'metaSection' => session('currentWarehouse') ? 'warehouse' : 'warehouses',
                    'sectionRoot' => 'inventory.dashboard'
                ],

                'headerData' => [
                    'title' => __('Inventory'),


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
            'inventory.dashboard' => [
                'route' => 'inventory.dashboard',
                'name'  => __('Inventory'),
            ]
        ];
    }


}
