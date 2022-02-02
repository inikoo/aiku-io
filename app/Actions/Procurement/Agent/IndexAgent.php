<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 02:17:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Agent;


use App\Actions\Procurement\ShowProcurementDashboard;
use App\Http\Resources\Procurement\AgentInertiaResource;
use App\Models\Procurement\Agent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;

class IndexAgent
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Agent::class)
            ->select('agents.id', 'code', 'name', 'number_suppliers', 'number_purchase_orders')
            ->leftJoin('agent_stats', 'agents.id', '=', 'agent_stats.agent_id')
            ->allowedSorts(['code', 'name', 'number_suppliers', 'number_purchase_orders'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia()
    {
        $this->validateAttributes();


        return Inertia::render(
            'Common/IndexModel',
            [
                'headerData' => [
                    'module'      => 'procurement',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $this->getBreadcrumbs(),

                ],
                'dataTable'  => [
                    'records' => AgentInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code'                   => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'procurement.agents.show',
                                'column' => 'id',
                            ],
                        ],
                        'name'                   => [
                            'sort'  => 'name',
                            'label' => __('Name')
                        ],
                        'number_suppliers'       => [
                            'sort'  => 'number_suppliers',
                            'label' => __('Suppliers'),
                            'href'  => [
                                'route'  => 'procurement.agents.show.suppliers.index',
                                'column' => 'id',
                            ],
                        ],
                        'number_purchase_orders' => [
                            'sort'  => 'number_purchase_orders',
                            'label' => __('Purchase orders'),
                            'href'  => [
                                'route'  => 'procurement.agents.show.purchase_orders.index',
                                'column' => 'id',
                            ],

                        ],

                    ]
                ]


            ]
        )->table(function (InertiaTable $table) {
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Agents'),
            ]
        );
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowProcurementDashboard())->getBreadcrumbs(),
            [
                'index' => [
                    'route'   => 'procurement.agents.index',
                    'name'    => __('Agents'),
                    'current' => false
                ],
            ]
        );
    }


}
