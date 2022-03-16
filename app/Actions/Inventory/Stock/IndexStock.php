<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 21:55:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Stock;


use App\Http\Resources\Inventory\StockInertiaResource;
use App\Models\Inventory\Stock;
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
 */
class IndexStock
{
    use AsAction;
    use WithInertia;

    protected array $select;
    protected array $columns;
    protected array $allowedSorts;


    public function __construct()
    {
        $this->select       = ['id', 'code', 'description'];
        $this->allowedSorts = ['code'];
        $this->columns =[

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
                                    'route'   => 'inventory.stocks.show',
                                    'indices' => 'id'
                                ],
                                'indices' => 'code'
                            ],
                        ]
                    ]
                ],
            ],
            'description' => [
                'label'    => __('Description'),
                'resolver' => 'description'
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

        return QueryBuilder::for(Stock::class)
            ->when(true, [$this, 'queryConditions'])
            ->allowedSorts($this->allowedSorts)
            ->defaultSort('-id')
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
                []
            );
        });
    }

    protected function getRecords(): AnonymousResourceCollection
    {
        return StockInertiaResource::collection($this->handle());
    }





}

