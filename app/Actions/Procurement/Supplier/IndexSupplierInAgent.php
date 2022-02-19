<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Feb 2022 23:28:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;

use App\Actions\Procurement\Agent\ShowAgent;
use App\Models\Procurement\Agent;
use Lorisleiva\Actions\ActionRequest;

use function __;

/**
 * @property Agent $agent
 */
class IndexSupplierInAgent extends IndexSupplier
{

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("procurement.agents.view");
    }

    public function queryConditions($query){
        return $query->where('owner_type', 'Agent')->where('owner_id',$this->agent->id);

    }


    public function asInertia(Agent $agent)
    {
        $this->set('agent', $agent);
        $this->validateAttributes();
        return $this->getInertia();

    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' =>__('Suppliers'),
                'inModel'=>[
                    'model'=>__('agent'),
                    'modelName'=>$this->agent->code
                ],
                'breadcrumbs'=>$this->getBreadcrumbs($this->agent),
                'sectionRoot'=>'procurement.agents.index',
                'hrefSupplier'=>[
                    'route'  => 'procurement.agents.show.suppliers.show',
                    'column' => ['owner_id','id'],
                ],
                'hrefPurchaseOrder'=>[
                    'route'  => 'procurement.agents.show.suppliers.show.purchase_orders.index',
                    'column' => ['owner_id','id'],
                ],
            ]
        );
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Agent $agent): array
    {
        return array_merge(
            (new ShowAgent())->getBreadcrumbs($agent),
            [
                'procurement.agents.show.suppliers.index' => [
                    'route'   => 'procurement.agents.show.suppliers.index',
                    'routeParameters'=>$agent->id,

                    'modelLabel'=>[
                        'label'=>__('suppliers')
                    ],
                ],
            ]
        );
    }


}
