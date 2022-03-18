<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 06:15:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder;


use App\Actions\Procurement\ShowProcurementDashboard;
use Lorisleiva\Actions\ActionRequest;


use function __;


class IndexPurchaseOrderInTenant extends IndexPurchaseOrder
{



    public function asInertia()
    {
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'              => __('Purchase orders'),
                'breadcrumbs'        => $this->getBreadcrumbs(),
                'sectionRoot'        => 'procurement.purchase_orders.index',
                'module'             => 'procurement',
                'metaSection'        =>  null,
                'tabRoute'           => 'procurement.purchase_orders.state',
                'tabRouteParameters' => []

            ]
        );

        $this->fillFromRequest($request);

        $this->set('tabs',$this->getTabs('all'));
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowProcurementDashboard())->getBreadcrumbs(),
            [
                'procurement.purchase_orders.index' => [
                    'route'      => 'procurement.purchase_orders.index',
                    'modelLabel' => [
                        'label' => __('purchase orders')
                    ],
                ],
            ]
        );
    }


}

