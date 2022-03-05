<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 05 Mar 2022 02:59:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;


use App\Http\Resources\Inventory\WarehouseAreaInertiaResource;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use App\Traits\HasDBDriverAwareQueries;
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
 * @property Warehouse $warehouse
 * @property array $breadcrumbs
 * @property string $sectionRoot
 * @property string $title
 * @property string $metaSection
 * @property array $allowedSorts
 * @property string $module
 */
class IndexWarehouseArea
{
    use AsAction;
    use WithInertia;
    use HasDBDriverAwareQueries;


    protected array $select;
    protected array $columns;
    protected array $allowedSorts;

    public function __construct()
    {
        $this->select       = ['warehouse_areas.id as id', 'code', 'warehouse_id', 'number_locations', 'name'];
        $this->allowedSorts = ['code', 'name', 'number_locations'];

        $this->columns = [
            'code' => [
                'sort'       => 'code',
                'label'      => __('Code'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'inventory.warehouses.show.areas.show',
                                    'indices' => ['warehouse_id', 'id'],
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

            'number_locations' => [
                'sort'       => 'number_locations',
                'label'      => __('Locations'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'inventory.warehouses.show.areas.show.locations.index',
                                    'indices' => ['warehouse_id', 'id'],
                                ],
                                'indices' => 'number_locations'
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
                $query->where('warehouse_areas.code', $this->likeOperator(), "$value%")
                    ->orWhere('warehouse_areas.name', $this->likeOperator(), "%$value%");
            });
        });


        return QueryBuilder::for(WarehouseArea::class)
            ->leftJoin('warehouse_area_stats', 'warehouse_areas.id', '=', 'warehouse_area_stats.warehouse_area_id')
            ->where('warehouse_id', $this->warehouse->id)
            ->allowedSorts($this->allowedSorts)
            ->allowedFilters([$globalSearch])
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
                    'title' => $this->title
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
        return WarehouseAreaInertiaResource::collection($this->handle());
    }


}
