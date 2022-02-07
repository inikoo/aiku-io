<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 03:48:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;


use App\Actions\Procurement\Agent\ShowAgent;
use App\Actions\Procurement\ShowProcurementDashboard;
use App\Http\Resources\Procurement\SupplierInertiaResource;
use App\Models\Account\Tenant;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;


/**
 * @property Tenant|Agent $parent
 */
class IndexSupplier
{
    use AsAction;
    use WithInertia;


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Supplier::class)
            ->select('suppliers.id', 'code', 'name', 'number_purchase_orders','location')
            ->leftJoin('supplier_stats', 'suppliers.id', '=', 'supplier_stats.supplier_id')
            ->when(class_basename($this->parent::class)=='Tenant', function ($query) {
                return $query->where('owner_type', 'Tenant');
            })
            ->when(class_basename($this->parent::class)=='Agent', function ($query) {
                return $query->where('owner_type', 'Agent')->where('owner_id',$this->parent->id);
            })
            ->allowedSorts(['code', 'name', 'number_purchase_orders'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia(Tenant|Agent $parent)
    {
        $this->set('parent', $parent);
        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'headerData' => [
                    'module'      => 'procurement',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => $this->getBreadcrumbs($this->parent),

                ],
                'dataTable'  => [
                    'records' => SupplierInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code' => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  => 'procurement.suppliers.show',
                                'column' => 'id',
                            ],
                        ],
                        'name' => [
                            'sort'  => 'name',
                            'label' => __('Name')
                        ],
                        'location'                   => [
                            'label' => __('Location'),
                            'location'=>true,
                        ],
                        'number_purchase_orders' => [
                            'sort'  => 'number_purchase_orders',
                            'label' => __('Purchase orders'),
                            'href'  => [
                                'route'  => 'procurement.suppliers.show.purchase_orders.index',
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
                'title' => match (class_basename($this->parent::class)) {
                    'Agent' => __('Agent').' '.$this->parent->code.': '.__('suppliers'),
                    default => __('Suppliers')
                },
            ]
        );
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Tenant|Agent $parent): array
    {
        return
            match (class_basename($parent::class)) {
                'Agent' => array_merge(
                    (new ShowAgent())->getBreadcrumbs($parent),
                    [
                        'procurement.agents.show.suppliers.index' => [
                            'route'   => 'procurement.agents.show.suppliers.index',
                            'routeParameters'=>$parent->id,
                            'name'    => __('suppliers'),
                        ],
                    ]
                ),
                default => array_merge(
                    (new ShowProcurementDashboard())->getBreadcrumbs(),
                    [
                        'procurement.suppliers.index' => [
                            'route'   => 'procurement.suppliers.index',
                            'name'    => __('suppliers'),
                        ],
                    ]
                )
            };
    }


}
