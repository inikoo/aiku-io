<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 03:48:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;

use App\Actions\Procurement\ShowProcurementDashboard;
use Lorisleiva\Actions\ActionRequest;

use function __;

class IndexSupplierInTenant extends IndexSupplier
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.suppliers.view");
    }

    public function queryConditions($query)
    {
        return $query->where('owner_type', 'Tenant');
    }

    public function asInertia()
    {
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'             => __('Suppliers'),
                'breadcrumbs'       => $this->getBreadcrumbs(),
                'sectionRoot'       => 'procurement.suppliers.index',
                'hrefSupplier'      => [
                    'route'  => 'procurement.suppliers.show',
                    'column' => 'id',
                ],
                'hrefPurchaseOrder' => [
                    'route'  => 'procurement.suppliers.show.purchase_orders.index',
                    'column' => 'id',
                ],
            ]
        );
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(): array
    {
        return
            array_merge(
                (new ShowProcurementDashboard())->getBreadcrumbs(),
                [
                    'procurement.suppliers.index' => [
                        'route'      => 'procurement.suppliers.index',
                        'modelLabel' => [
                            'label' => __('suppliers')
                        ],
                    ],
                ]
            );
    }


}
