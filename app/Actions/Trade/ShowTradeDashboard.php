<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Feb 2022 00:23:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade;


use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class ShowTradeDashboard
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


        return Inertia::render(
            'show-dashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navLocation' => ['module' => 'shops', 'metaSection' => 'shops', 'sectionRoot' => 'shops.dashboard'],
                'headerData'  => [
                    'title' => __('Shops dashboard'),
                ]
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }


    private function getBreadcrumbs(): array
    {
        return [
            'warehouse' => [
                'route' => 'shops.dashboard',
                'name'  => __('Shops dashboard'),
            ]
        ];
    }


}
