<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 23:43:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder;


use App\Actions\Procurement\Supplier\ShowSupplierInTenant;
use App\Models\Procurement\Supplier;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property \App\Models\Procurement\Supplier $supplier
 */
class IndexPurchaseOrderInSupplier extends IndexPurchaseOrder
{

    public function queryConditions($query)
    {
        return $query
            ->where('vendor_type', 'Supplier')
            ->where('vendor_id', $this->supplier->id)
            ->select($this->select);
    }

    public function asInertia(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'       => __('Purchase orders'),
                'breadcrumbs' => $this->getBreadcrumbs($this->supplier),
                'sectionRoot' => 'procurement.suppliers.index',
                'module'      => 'procurement',
                'metaSection' => null,


            ]
        );

        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(Supplier $supplier): array
    {
        return array_merge(
            (new ShowSupplierInTenant())->getBreadcrumbs($supplier),
            [
                'procurement.suppliers.show.purchase_orders.index' => [
                    'route'           => 'procurement.suppliers.show.purchase_orders.index',
                    'routeParameters' => [$supplier->id],
                    'modelLabel'      => [
                        'label' => __('purchase orders')
                    ],
                ],
            ]
        );
    }


}

