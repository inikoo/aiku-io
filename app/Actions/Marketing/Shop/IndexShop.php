<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:06:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Shop;


use App\Actions\Marketing\ShowMarketingDashboard;
use App\Http\Resources\Marketing\ShopInertiaResource;
use App\Models\Marketing\Shop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;


class IndexShop
{
    use AsAction;
    use WithInertia;

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Shop::class)
            ->allowedSorts(['code', 'name'])
            ->paginate()
            ->withQueryString();
    }




    public function asInertia($module = false)
    {


        $this->set('module', $module);
        $this->validateAttributes();


        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData' => ['module' => 'marketing', 'metaSection' => 'shops', 'sectionRoot' => 'marketing.shops.index'],

                'headerData' => [
                    'title'       => __('Shops'),

                ],
                'dataTable'  => [
                    'records' => ShopInertiaResource::collection($this->handle()),
                    'columns' => [
                        'code'      => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  =>'marketing.shops.show',
                                'column' => 'id',
                                'with_permission'=>'can_view'

                            ],
                        ],
                        'name'          => [
                            'sort'  => 'name',
                            'label' => __('Name')
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




    public function getBreadcrumbs(): array
    {


        return array_merge(
            (new ShowMarketingDashboard())->getBreadcrumbs(),
            [
                'marketing.shops.index' => [
                    'route'   => 'marketing.shops.index',
                    'name'    => __('Shops'),
                ],
            ]
        );

    }


}
