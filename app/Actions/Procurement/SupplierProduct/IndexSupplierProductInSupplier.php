<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 04:25:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\SupplierProduct;



use App\Actions\Procurement\Supplier\ShowSupplierInTenant;
use App\Models\Procurement\Supplier;
use Lorisleiva\Actions\ActionRequest;


use function __;


/**
 * @property Supplier $supplier
 */
class IndexSupplierProductInSupplier extends IndexSupplierProduct
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.suppliers.view.{$this->supplier->id}");
    }

    public function queryConditions($query)
    {
        return $query
            ->where('supplier_id',$this->supplier->id)
            ->select($this->select);
    }

    public function asInertia(Supplier $supplier)
    {
        $this->supplier=$supplier;
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'              => __('Products'),
                'breadcrumbs'        => $this->getBreadcrumbs($this->supplier),
                'sectionRoot'        => 'procurement.suppliers.index',
                'module'             => 'procurement',
                'metaSection'        =>  null,


            ]
        );

        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(Supplier $supplier): array
    {
        return array_merge(
            (new ShowSupplierInTenant())->getBreadcrumbs($supplier),
            [
                'procurement.suppliers.show.products.index' => [
                    'route'      => 'procurement.suppliers.show.products.index',
                    'routeParameters'=>[$supplier->id],
                    'modelLabel' => [
                        'label' => __('products')
                    ],
                ],
            ]
        );
    }


}

