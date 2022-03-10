<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 07 Mar 2022 19:57:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkTarget;


use App\Http\Resources\HumanResources\WorkTargetInertiaResource;
use App\Models\HumanResources\WorkTarget;
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
 * @property string $tabRoute
 * @property mixed $tabRouteParameter
 * @property array $intervalTabs
 * @property mixed $tabRouteParameters
 */
class IndexWorkTarget
{
    use AsAction;
    use WithInertia;

    protected array $select;
    protected array $columns;
    protected array $allowedSorts;
    private array $intervals;

    public function __construct()
    {
        $this->select       = [
            'work_targets.id as id',
            'employee_id',
            'employees.name as employee_name',
            'employees.nickname as employee_nickname',
            'date'


        ];
        $this->allowedSorts = ['date'];


        $this->columns = [

            'id' => [
                'sort'       => 'id',
                'label'      => __('Id'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'human_resources.timesheets.show',
                                    'indices' => ['id']
                                ],
                                'indices' => 'formatted_id'
                            ],
                        ]
                    ]
                ],
            ],

            'employee_name' => [
                'sort'       => 'employee_name',
                'label'      => __('Employee'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'human_resources.employees.show',
                                    'indices' => ['employee_id']
                                ],
                                'indices' => 'employee_name'
                            ],
                        ]
                    ]
                ],
            ],

            'date' => [
                'sort'       => 'date',
                'label'      => __('Date'),
                'type'=>'date',
                'resolver' =>'date'


            ],


        ];
    }

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(WorkTarget::class)
            ->when(true, [$this, 'queryConditions'])
            ->leftJoin('employees', 'employee_id', 'employees.id')
            ->defaultSorts('-id')
            ->allowedSorts($this->allowedSorts)
            ->paginate()
            ->withQueryString();
    }

    protected function getIntervalTabs($current = false): array
    {
        return [
            'left'  => [
                'today'     => [
                    'name'    => __('Today'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['today'])
                    ],
                    'current' => $current === 'today',
                ],
                'yesterday' => [
                    'name'    => __('Yesterday'),
                    'href'    => [
                        'route'      => $this->tabRoute,
                        'parameters' => array_merge($this->tabRouteParameters, ['yesterday'])
                    ],
                    'current' => $current === 'yesterday',
                ]
            ],
            'right' => [
                'all' => [
                    'class'   => '',
                    'name'    => __('All'),
                    'href'    => [
                        'route' => 'human_resources.timesheets.index',
                    ],
                    'current' => $current === 'all',
                ],
            ]
        ];
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


                'intervalTabs' => $this->intervalTabs,

                'dataTable' => [
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
        return WorkTargetInertiaResource::collection($this->handle());
    }


}
