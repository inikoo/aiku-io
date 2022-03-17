<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 02:18:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\SupplierProduct;


use App\Actions\Procurement\ShowProcurementDashboard;
use App\Enums\AvailabilityStatus;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property \App\Enums\AvailabilityStatus $availabilityStatus
 */
class IndexSupplierProductInTenantWithAvailabilityStatus extends IndexSupplierProduct
{

    public function queryConditions($query)
    {
        return $query->where('stock_quantity_status',$this->availabilityStatus->value)
        ->select($this->select);
    }

    public function asInertia(AvailabilityStatus $availabilityStatus)
    {
        $this->availabilityStatus = $availabilityStatus;
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'              => match ($this->availabilityStatus->name) {
                    'Surplus' => __('Products with surplus stock'),
                    'Optimal' => __('Products with good stock'),
                    'Low' => __('Products with low stock'),
                    'Critical' => __('Products with critical low stock'),
                    'OutOfStock' => __('Products with no stock'),
                    'NoApplicable' => __('Products not stocked'),
                    default => __('Products')
                },
                'breadcrumbs'        => $this->getBreadcrumbs($this->availabilityStatus),
                'sectionRoot'        => 'procurement.products.index',
                'module'             => 'procurement',
                'metaSection'        => null,
                'tabRoute'           => 'procurement.products.stock_quantity_status',
                'tabRouteParameters' => []

            ]
        );

        $this->fillFromRequest($request);

        $this->set('tabs', $this->getTabs($this->availabilityStatus->value));
    }


    public function getBreadcrumbs(AvailabilityStatus $availabilityStatus): array
    {
        return array_merge(
            (new ShowProcurementDashboard())->getBreadcrumbs(),
            [
                'procurement.products.index' => [
                    'route'      => 'procurement.products.stock_quantity_status',
                    'routeParameters'=>$availabilityStatus->value,
                    'modelLabel' => [
                        'label' => __('products')
                    ],
                ],
            ]
        );
    }


}

