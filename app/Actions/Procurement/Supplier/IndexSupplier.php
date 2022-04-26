<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 18 Feb 2022 17:02:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;


use App\Http\Resources\Procurement\SupplierInertiaResource;
use App\Models\Procurement\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;


/**
 * @property array $breadcrumbs
 * @property string $sectionRoot
 * @property string $title
 * @property array $hrefSupplier
 * @property array $hrefPurchaseOrder
 */
class IndexSupplier
{
    use AsAction;
    use WithInertia;

    protected ?array $createSupplier;
    protected array $select;
    protected array $columns;
    protected array $allowedSorts;

    public function __construct()
    {
        $this->createSupplier = null;
        $this->select         = ['owner_id', 'suppliers.id', 'code', 'name', 'number_purchase_orders', 'number_products', 'location'];
        $this->allowedSorts   = ['code', 'name', 'number_products', 'number_purchase_orders'];

        $this->columns = [


            'code' => [
                'sort'       => 'code',
                'label'      => __('Code'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type' => 'link',

                            'parameters' => [
                                'href'    => [
                                    'route'   => 'procurement.suppliers.show',
                                    'indices' => 'id'
                                ],
                                'indices' => 'code'
                            ],


                        ]
                    ]
                ],

            ],


            'name' => [
                'sort'     => 'name',
                'label'    => __('Name'),
                'resolver' => 'name'
            ],

            'location' => [
                'label'      => __('Location'),
                'components' => [
                    [
                        'type'     => 'flag',
                        'resolver' => [
                            'type'       => 'country',
                            'parameters' => [
                                'indices' => 'country'
                            ],


                        ]
                    ],
                    [
                        'type'     => 'text',
                        'resolver' => [
                            'type' => 'slot',

                            'parameters' => [

                                'indices' => 'location'
                            ],


                        ]
                    ]
                ],
            ],


            'number_purchase_orders' => [
                'sort'       => 'number_purchase_orders',
                'label'      => __('Purchase orders'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type' => 'link',

                            'parameters' => [
                                'href'    => [
                                    'route'   => 'procurement.suppliers.show.purchase_orders.index',
                                    'indices' => 'id'
                                ],
                                'indices' => 'number_purchase_orders'
                            ],


                        ]
                    ]
                ],

            ],
            'number_products'        => [
                'sort'       => 'number_products',
                'label'      => __('Products'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type' => 'link',

                            'parameters' => [
                                'href'    => [
                                    'route'   => 'procurement.suppliers.show.products.index',
                                    'indices' => 'id'
                                ],
                                'indices' => 'number_products'
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
        return QueryBuilder::for(Supplier::class)
            ->select($this->select)
            ->leftJoin('supplier_stats', 'suppliers.id', '=', 'supplier_stats.supplier_id')
            ->when(true, [$this, 'queryConditions'])
            ->defaultSorts('-id')
            ->allowedSorts($this->allowedSorts)
            ->paginate()
            ->withQueryString();
    }


    public function getInertia()
    {
        $this->columns['code']['href']                   = $this->hrefSupplier;
        $this->columns['number_purchase_orders']['href'] = $this->hrefPurchaseOrder;


        $headerData = [
            'title' => $this->title,
        ];
        if (!empty($this->inModel)) {
            $headerData['inModel'] = $this->inModel;
        }


        if ($this->createSupplier) {
            $headerData['actionIcons'][] = [
                'name'            => __('Create supplier'),
                'icon'            => ['fal', 'plus'],
                'route'           => $this->createSupplier['route'],
                'routeParameters' => $this->createSupplier['route'] ?? null
            ];
        }

        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->breadcrumbs,
                'navData'     => ['module' => 'procurement', 'sectionRoot' => $this->sectionRoot],

                'headerData' => $headerData,
                'dataTable'  => [
                    'records' => SupplierInertiaResource::collection($this->handle()),
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


}
