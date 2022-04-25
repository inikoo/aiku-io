<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Mar 2022 02:49:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\SupplierProduct;

use App\Actions\Procurement\ShowProcurementDashboard;
use App\Actions\Procurement\Supplier\ShowSupplierInTenant;
use App\Actions\UI\WithInertia;
use App\Models\Procurement\SupplierProduct;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property SupplierProduct $supplierProduct
 * @property string $parent
 * @property bool $canEdit
 */
class ShowSupplierProduct
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


    public function asInertia(string $parent, SupplierProduct $supplierProduct): Response
    {
        $this->parent          = $parent;
        $this->supplierProduct = $supplierProduct;

        $this->validateAttributes();


        $actionIcons = [];
        if ($this->canEdit) {
            $actionIcons[] = [
                'route'           => 'procurement.products.edit',
                'routeParameters' => $this->supplierProduct->id,
                'name'            => __('Edit'),
                'icon'            => ['fal', 'edit']
            ];
        }


        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->parent, $this->supplierProduct),
                'navData'     => ['module' => 'procurement', 'sectionRoot' => 'procurement.suppliers.index'],
                'headerData'  => [
                    'title'       => $this->supplierProduct->code,
                    'actionIcons' => $actionIcons,

                ],
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
        $this->set('canEdit', $request->user()->can("procurement.suppliers.edit"));
    }


    public function getBreadcrumbs(string $parent, SupplierProduct $supplierProduct): array
    {
        if ($parent == 'supplier') {
            return array_merge(
                (new ShowSupplierInTenant())->getBreadcrumbs($this->supplierProduct->supplier),
                [
                    'procurement.suppliers.show.products.show' => [
                        'route'           => 'procurement.suppliers.show.products.show',
                        'routeParameters' => [$this->supplierProduct->supplier_id, $this->supplierProduct->id],
                        'index'           => [
                            'route'           => 'procurement.suppliers.show.products.index',
                            'routeParameters' => [$this->supplierProduct->supplier_id],

                            'overlay' => __('Product index')
                        ],
                        'modelLabel'      => [
                            'label' => __('product')
                        ],
                        'name'            => $supplierProduct->code,

                    ],
                ]
            );
        }

        return array_merge(
            (new ShowProcurementDashboard())->getBreadcrumbs(),
            [
                'procurement.products.show' => [
                    'route'           => 'procurement.products.show',
                    'routeParameters' => $supplierProduct->id,
                    'index'           => [
                        'route'   => 'procurement.products.index',
                        'overlay' => __('Product index')
                    ],
                    'modelLabel'      => [
                        'label' => __('product')
                    ],
                    'name'            => $supplierProduct->code,

                ],
            ]
        );
    }


}
