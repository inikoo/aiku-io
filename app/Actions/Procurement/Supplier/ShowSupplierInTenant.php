<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 19 Feb 2022 04:41:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;


use App\Actions\Procurement\ShowProcurementDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Procurement\Supplier;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Supplier $supplier
 */
class ShowSupplierInTenant extends ShowSupplier
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.suppliers.view") ;
    }


    public function asInertia(Supplier $supplier, array $attributes = []): Response
    {
        $this->set('supplier', $supplier)->fill($attributes);

        $this->validateAttributes();


        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' =>$this->supplier->name,
                'breadcrumbs'=>$this->getBreadcrumbs($this->supplier),
                'sectionRoot'=>'procurement.suppliers.index',
                'edit'=>[
                    'can'=> $request->user()->hasPermissionTo("procurement.supplier.edit.{$this->supplier->id}"),
                    'route'=>'procurement.suppliers.edit',
                    'routeParameters'=>$this->supplier->id,
                ]

            ]
        );

        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(Supplier $supplier): array
    {
        return array_merge(
            (new ShowProcurementDashboard())->getBreadcrumbs(),
            [
                'procurement.suppliers.show' => [
                    'route'           => 'procurement.suppliers.show',
                    'routeParameters' => $supplier->id,
                    'name'            => $supplier->code,
                    'index'=>[
                        'route'   => 'procurement.suppliers.index',
                        'overlay' => __('Suppliers index')
                    ],
                    'modelLabel'=>[
                        'label'=>__('supplier')
                    ],

                ],
            ]
        );
    }


}
