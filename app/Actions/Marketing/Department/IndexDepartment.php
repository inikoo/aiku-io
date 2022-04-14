<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 13 Apr 2022 18:25:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Department;


use App\Actions\Marketing\Shop\ShowShop;
use App\Actions\Marketing\UseCatalogue;
use App\Http\Resources\Marketing\DepartmentInertiaResource;
use App\Models\Marketing\Department;
use App\Models\Marketing\Shop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;


use function __;


/**
 * @property \App\Models\Marketing\Shop $shop
 */
class IndexDepartment
{
    use AsAction;
    use WithInertia;
    use UseCatalogue;

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Department::class)
            ->allowedSorts(['code', 'name','shop_id'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia(Shop $shop)
    {
        $this->shop=$shop;
        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($this->shop),
                'navData'     => ['module' => 'marketing', 'metaSection' => 'shop', 'sectionRoot' => 'marketing.shops.show.catalogue'],

                'headerData' => [
                    'title' => __('Departments'),
                    'topTabs'=>$this->getCatalogueTabs('departments')

                ],
                'dataTable'  => [
                    'records' => DepartmentInertiaResource::collection($this->handle()),
                    'columns' => [

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
                                                'route'      => 'marketing.shops.show.departments.index',
                                                'indices'    => ['shop_id','id'],
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
                    ]
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [


                ]
            );
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(Shop $shop): array
    {
        return array_merge(
            (new ShowShop())->getBreadcrumbs($shop),
            [
                'marketing.shops.index' => [
                    'route'      => 'marketing.shops.show.departments.index',
                    'routeParameters'=>[$shop->id],
                    'modelLabel' => [
                        'label' => __('departments')
                    ],
                ],
            ]
        );
    }



}
