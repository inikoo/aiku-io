<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Mar 2022 16:32:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\UniqueStock;


use App\Http\Resources\Inventory\UniqueStockInertiaResource;
use App\Models\Inventory\UniqueStock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;

use App\Actions\UI\WithInertia;

use Spatie\QueryBuilder\QueryBuilder;

use function __;


/**
 * @property array $breadcrumbs
 * @property string $sectionRoot
 * @property string $title
 * @property string $metaSection
 * @property array $allowedSorts
 * @property string $subtitle
 * @property string $module
 */
class IndexUniqueStock
{
    use AsAction;
    use WithInertia;


    protected array $select;
    protected array $columns;
    protected array $allowedSorts;

    public function __construct()
    {
        $this->select       = [
            'unique_stocks.id as id',
            'reference',
            'customers.name as customer_name',
            'customers.id as customer_id',
            'customers.shop_id as shop_id'
        ];
        $this->allowedSorts = ['reference', 'id'];

        $this->columns = [


            'formatted_id' => [
                'sort'       => 'id',
                'label'      => __('Id'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'inventory.unique_stocks.show',
                                    'indices' => 'id'
                                ],
                                'indices' => 'formatted_id'
                            ],
                        ]
                    ]
                ],
            ],
            'customer'     => [
                'sort'       => 'customer_name',
                'label'      => __('Customer'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'marketing.shops.show.customers.show',
                                    'indices' => ['shop_id', 'customer_id']
                                ],
                                'indices' => 'customer_name'
                            ],
                        ]
                    ]
                ],
            ],
            'reference'    => [
                'sort'     => 'reference',
                'label'    => __('Customer reference'),
                'resolver' => 'reference'
            ]
        ];
    }

    public function queryConditions($query)
    {
        return $query->select($this->select);
    }

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(UniqueStock::class)
            ->join('customers', 'customer_id', 'customers.id')
            ->when(true, [$this, 'queryConditions'])
            ->defaultSorts('-unique_stocks.id')
            ->allowedSorts($this->allowedSorts)
            ->paginate()
            ->withQueryString();
    }


    public function getInertia()
    {
        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->breadcrumbs,
                'navData'     => ['module' => $this->module, 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],

                'headerData' => [
                    'title'    => $this->title,
                    'subtitle' => $this->subtitle

                ],
                'dataTable'  => [
                    'records' => $this->getRecords(),
                    'columns' => $this->columns
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [


                ]
            );
        });
    }

    protected function getRecords(): AnonymousResourceCollection
    {
        return UniqueStockInertiaResource::collection($this->handle());
    }

}
