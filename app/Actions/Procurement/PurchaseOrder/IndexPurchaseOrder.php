<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 16:03:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\PurchaseOrder;


use App\Http\Resources\Procurement\PurchaseOrderInertiaResource;
use App\Models\Procurement\PurchaseOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;

/**
 * @property array $breadcrumbs
 * @property string $sectionRoot
 * @property string $title
 * @property string $metaSection
 * @property array $allowedSorts
 * @property string $module
 * @property string $tabRoute
 * @property array $tabRouteParameters
 * @property ?array $tabs
 */
class IndexPurchaseOrder
{
    use AsAction;
    use WithInertia;

    protected array $select;
    protected array $columns;
    protected array $allowedSorts;
    protected int $perPage;


    public function __construct()
    {
        $this->select       = ['purchase_orders.id', 'number'];
        $this->perPage      = 15;
        $this->allowedSorts = ['number'];
        $this->columns      = [

            'code'        => [
                'sort'       => 'number',
                'label'      => __('Number'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'procurement.purchase_orders.show',
                                    'indices' => 'id'
                                ],
                                'indices' => 'number'
                            ],
                        ]
                    ]
                ],
            ],


        ];
    }

    public function queryConditions($query)
    {
        return $query->select($this->select);
    }

    public function handle(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('description', 'LIKE', "%$value%")
                    ->orWhere('code', 'LIKE', "$value%");
            });
        });

        return QueryBuilder::for(PurchaseOrder::class)
            ->when(true, [$this, 'queryConditions'])
            ->allowedSorts($this->allowedSorts)
            ->defaultSort('-purchase_orders.id')
            ->allowedFilters([$globalSearch])
            ->paginate($this->perPage)
            ->withQueryString();
    }

    public function getInertia()
    {
        $blueprints = [
            'breadcrumbs' => $this->breadcrumbs,
            'navData'     => ['module' => $this->module, 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],
            'headerData'  => [
                'title' => $this->title
            ],
            'dataTable'   => [
                'records' => $this->getRecords(),
                'columns' => $this->columns
            ]

        ];
        if ($this->tabs) {
            $blueprints['tabs'] = $this->tabs;
        }


        return Inertia::render(
            'index-model',
            $blueprints
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                []
            );
        });
    }

    protected function getRecords(): AnonymousResourceCollection
    {
        return PurchaseOrderInertiaResource::collection($this->handle());
    }


    protected function getTabs($current = false): array
    {
        return [
            'left'  => [
                'in-process'        => [
                    'name'    => __('In process'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['in-process'])
                    ],
                    'current' => $current === 'in-process',
                ],
                'submitted'    => [
                    'name'    => __('Submitted'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['submitted'])
                    ],
                    'current' => $current === 'submitted',
                ],
                'confirmed' => [
                    'name'    => __('Confirmed'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['confirmed'])
                    ],
                    'current' => $current === 'confirmed',
                ],
                'dispatched'  => [
                    'name'    => __('Dispatched'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['dispatched'])
                    ],
                    'current' => $current === 'dispatched',
                ],
                'delivered'  => [
                    'name'    => __('Delivered'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['delivered'])
                    ],
                    'current' => $current === 'delivered',
                ],
                'cancelled'  => [
                    'name'    => __('Cancelled'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['cancelled'])
                    ],
                    'current' => $current === 'cancelled',
                ],
            ],
            'right' => [
                'all' => [
                    'class'   => '',
                    'name'    => __('All'),
                    'href'    => [
                        'route' => 'procurement.purchase_orders.index',
                    ],
                    'current' => $current === 'all',
                ],
            ]
        ];
    }


}

