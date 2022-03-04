<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Mar 2022 17:18:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;


use App\Actions\Inventory\ShowInventoryDashboard;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


use App\Actions\UI\WithInertia;

use function __;

/**
 * @property \Illuminate\Support\Collection $allowed_shops
 * @property bool $canViewAll
 */
class IndexUniqueStockInTenant extends IndexUniqueStock
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("inventory.stocks.view");
    }

    public function queryConditions($query)
    {
        return $query->select($this->select);
    }


    public function asInertia()
    {
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->allowedSorts = array_merge(
            $this->allowedSorts,
            ['customer_name']
        );

        $request->merge(
            [
                'title'       => __('Stored goods'),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'sectionRoot' => 'inventory.unique_stocks.index',
            ]
        );
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowInventoryDashboard())->getBreadcrumbs(),
            [
                'inventory.unique_stocks.index' => [
                    'route'      => 'inventory.unique_stocks.index',
                    'modelLabel' => [
                        'label' => __('stored goods')
                    ],
                ],
            ]
        );
    }


}
