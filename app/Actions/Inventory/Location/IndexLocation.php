<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 29 Jan 2022 01:55:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Location;


use App\Http\Resources\Inventory\LocationInertiaResource;
use App\Models\Inventory\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
 * @property string $metaSection
 * @property array $allowedSorts
 * @property string $module
 */
class IndexLocation
{
    use AsAction;
    use WithInertia;

    protected array $select;
    protected array $columns;
    protected array $allowedSorts;

    public function __construct()
    {
        $this->select       = [
            'locations.id as id',
            'locations.code as code',
            'locations.warehouse_id',
            'locations.warehouse_area_id',


        ];
        $this->allowedSorts = ['code'];

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
                                    'route'   => 'inventory.locations.show',
                                    'indices' => ['id']
                                ],
                                'indices' => 'code'
                            ],
                        ]
                    ]
                ],
            ],

            'warehouse_code' => [
                'sort'       => 'warehouse_code',
                'label'      => __('Warehouse'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'inventory.warehouses.show',
                                    'indices' => ['warehouse_id']
                                ],
                                'indices' => 'warehouse_code',
                            ],
                        ]
                    ]
                ],
            ],


            'warehouse_area_code' => [
                'sort'       => 'warehouse_area_code',
                'label'      => __('Area'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'        => [
                                    'route'   => 'inventory.areas.show',
                                    'indices' => ['warehouse_area_id']
                                ],
                                'indices'     => 'warehouse_area_code',
                                'notSetLabel' => __('Not set')
                            ],
                        ]
                    ]
                ],
            ],




        ];
    }

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Location::class)
            ->when(true, [$this, 'queryConditions'])
            ->defaultSorts('-id')
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
                'headerData'  => [
                    'title' => $this->title
                ],
                'dataTable'   => [
                    'records' => $this->getRecords(),
                    'columns' => $this->columns
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                []
            );
        });
    }

    protected function getRecords(): AnonymousResourceCollection
    {
        return LocationInertiaResource::collection($this->handle());
    }


}
