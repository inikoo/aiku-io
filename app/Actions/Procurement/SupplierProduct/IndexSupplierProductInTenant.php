<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 00:48:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\SupplierProduct;


use App\Actions\Procurement\ShowProcurementDashboard;
use Lorisleiva\Actions\ActionRequest;


use function __;


class IndexSupplierProductInTenant extends IndexSupplierProduct
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
                'title'              => __('Products'),
                'breadcrumbs'        => $this->getBreadcrumbs(),
                'sectionRoot'        => 'procurement.products.index',
                'module'             => 'procurement',
                'metaSection'        =>  null,
                'tabRoute'           => 'procurement.products.stock_quantity_status',
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
                'procurement.products.index' => [
                    'route'      => 'procurement.products.index',
                    'modelLabel' => [
                        'label' => __('products')
                    ],
                ],
            ]
        );
    }


}

