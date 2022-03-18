<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 23:18:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder\ByState;


use App\Actions\Procurement\PurchaseOrder\IndexPurchaseOrder;
use App\Actions\Procurement\ShowProcurementDashboard;
use App\Enums\PurchaseOrderState;
use Lorisleiva\Actions\ActionRequest;

use function __;


/**
 * @property \App\Enums\PurchaseOrderState $purchaseOrderState
 */
class IndexStatePurchaseOrderInTenant extends IndexPurchaseOrder
{



    public function queryConditions($query)
    {
        return $query
            ->where('state',$this->purchaseOrderState->value)
            ->select($this->select);
    }

    public function asInertia(PurchaseOrderState $purchaseOrderState)
    {
        $this->purchaseOrderState=$purchaseOrderState;
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'              => __('Purchase orders'),
                'breadcrumbs'        => $this->getBreadcrumbs($this->purchaseOrderState),
                'sectionRoot'        => 'procurement.purchase_orders.index',
                'module'             => 'procurement',
                'metaSection'        =>  null,
                'tabRoute'           => 'procurement.purchase_orders.state',
                'tabRouteParameters' => []

            ]
        );

        $this->fillFromRequest($request);

        $this->setStatePurchaseOrderVariables();

        $this->set('tabs',$this->getTabs($this->purchaseOrderState->value));
    }


    public function getBreadcrumbs(PurchaseOrderState $purchaseOrderState): array
    {
        return array_merge(
            (new ShowProcurementDashboard())->getBreadcrumbs(),
            [
                'procurement.purchase_orders.index' => [
                    'route'      => 'procurement.purchase_orders.state',
                    'routeParameters'=>[$purchaseOrderState->name],
                    'modelLabel' => [
                        'label' => __('purchase orders')
                    ],
                ],
            ]
        );
    }

    public function setStatePurchaseOrderVariables(){}

}

