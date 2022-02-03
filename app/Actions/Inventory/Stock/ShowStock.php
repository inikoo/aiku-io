<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 18:31:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Actions\UI\WithInertia;
use App\Models\Inventory\Stock;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Stock $stock
 */
class ShowStock
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


    public function asInertia(Stock $stock, array $attributes = []): Response
    {
        $this->set('stock', $stock)->fill($attributes);

        $this->validateAttributes();



        $actionIcons = [];
        if ($this->get('canEdit')) {
            $actionIcons['inventory.stocks.edit'] = [
                'routeParameters' => $this->stock->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'Common/ShowModel',
            [
                'headerData' => [
                    'module'      => 'stocks',
                    'title'       => $stock->code,
                    'breadcrumbs' => $this->getBreadcrumbs($this->stock),
                    'actionIcons' => $actionIcons,

                ],
                'model'      => $stock
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->can("inventory.stocks.edit"));

    }


    public function getBreadcrumbs(Stock $stock): array
    {
        return array_merge(
            (new IndexStock())->getBreadcrumbs(),
            [
                'stocks.show' => [
                    'route'           => 'inventory.stocks.show',
                    'routeParameters' => $stock->id,
                    'name'            => $stock->code,
                    'model'           => [
                        'label' => __('Stock'),
                        'icon'  => ['fal', 'box'],
                    ],

                ],
            ]
        );
    }


}
