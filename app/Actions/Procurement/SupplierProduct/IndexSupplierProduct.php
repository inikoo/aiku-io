<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 16:03:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\SupplierProduct;


use App\Http\Resources\Inventory\StockInertiaResource;
use App\Http\Resources\Procurement\SupplierProductInertiaResource;
use App\Models\Inventory\Stock;
use App\Models\Procurement\SupplierProduct;
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
class IndexSupplierProduct
{
    use AsAction;
    use WithInertia;

    protected array $select;
    protected array $columns;
    protected array $allowedSorts;
    protected int $perPage;


    public function __construct()
    {
        $this->select       = ['supplier_products.id', 'code', 'name'];
        $this->perPage      = 15;
        $this->allowedSorts = ['code'];
        $this->columns      = [

            'code'        => [
                'sort'       => 'code',
                'label'      => __('Code'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'procurement.products.show',
                                    'indices' => 'id'
                                ],
                                'indices' => 'code'
                            ],
                        ]
                    ]
                ],
            ],
            'name' => [
                'label'    => __('Name'),
                'resolver' => 'name'
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
                $query->where('name', 'LIKE', "%$value%")
                    ->orWhere('code', 'LIKE', "$value%");
            });
        });

        return QueryBuilder::for(SupplierProduct::class)
            ->when(true, [$this, 'queryConditions'])
            ->allowedSorts($this->allowedSorts)
            ->defaultSort('-supplier_products.id')
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
        return SupplierProductInertiaResource::collection($this->handle());
    }


    protected function getTabs($current = false): array
    {
        return [
            'left'  => [
                'active'        => [
                    'name'    => __('Active'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['active'])
                    ],
                    'current' => $current === 'active',
                ],
                'in-process'    => [
                    'name'    => __('In process'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['in-process'])
                    ],
                    'current' => $current === 'in-process',
                ],
                'discontinuing' => [
                    'name'    => __('Discontinuing'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['discontinuing'])
                    ],
                    'current' => $current === 'discontinuing',
                ],
                'discontinued'  => [
                    'name'    => __('Discontinued'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['discontinued'])
                    ],
                    'current' => $current === 'discontinued',
                ],
            ],
            'right' => [
                'all' => [
                    'class'   => '',
                    'name'    => __('All'),
                    'href'    => [
                        'route' => 'inventory.stocks.index',
                    ],
                    'current' => $current === 'all',
                ],
            ]
        ];
    }


}

