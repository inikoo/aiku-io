<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 14 Apr 2022 23:42:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Family;


use App\Http\Resources\Marketing\FamilyInertiaResource;
use App\Models\Marketing\Family;
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
 * @property string $module
 */
class IndexFamily
{
    use AsAction;
    use WithInertia;


    protected array $select;
    protected array $columns;
    protected array $allowedSorts;

    public function __construct()
    {
        $this->select       = ['id', 'code', 'name', 'shop_id', 'department_id'];
        $this->allowedSorts = ['code', 'name', 'id'];

        $this->columns = [

            'customer_number' => [
                'sort'       => 'id',
                'label'      => __('Id'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'marketing.shops.show.families.show',
                                    'indices' => ['shop_id', 'id']
                                ],
                                'indices' => 'code'
                            ],


                        ]
                    ]
                ],

            ],

            'shop_code' => [
                'sort'       => 'shop_code',
                'label'      => __('Shop'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type'       => 'link',
                            'parameters' => [
                                'href'    => [
                                    'route'   => 'marketing.shops.show',
                                    'indices' => 'shop_id'
                                ],
                                'indices' => 'shop_code'
                            ],


                        ]
                    ]
                ],

            ],

            'name' => [
                'sort'     => 'name',
                'label'    => __('Name'),
                'resolver' => 'name'
            ]
        ];
    }

    public function queryConditions($query)
    {
        return $query->select($this->select);
    }

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Family::class)
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
                    'title'   => $this->title,
                    'topTabs' => $this->topTabs??null

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
        return FamilyInertiaResource::collection($this->handle());
    }

}
