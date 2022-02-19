<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 19 Feb 2022 01:44:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;


use App\Actions\Procurement\Agent\ShowAgent;
use App\Actions\UI\WithInertia;
use App\Models\Procurement\Supplier;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Supplier $supplier
 */
class ShowSupplierInAgent extends ShowSupplier
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.agents.view") ;
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
                'inModel'=>[
                    'model'=>__('agent'),
                    'modelName'=>$this->supplier->owner->code
                ],
                'breadcrumbs'=>$this->getBreadcrumbs($this->supplier),
                'sectionRoot'=>'procurement.agents.index',
                'edit'=>[
                    'can'=> $request->user()->hasPermissionTo("procurement.agent.edit.{$this->supplier->owner->id}"),
                    'route'=>'procurement.agents.show.suppliers.edit',
                    'routeParameters'=>[$this->supplier->owner_id,$this->supplier->id],
                ]

            ]
        );

        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(Supplier $supplier): array
    {
        return array_merge(
            (new ShowAgent())->getBreadcrumbs($supplier->owner),
            [
                'procurement.agents.show.suppliers.show' => [
                    'route'           => 'procurement.agents.show.suppliers.show',
                    'routeParameters' => [$supplier->owner_id,$supplier->id],
                    'name'            => $supplier->code,
                    'index'=>[
                        'route'   => 'procurement.agents.show.suppliers.index',
                        'routeParameters' => $supplier->owner_id,
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
