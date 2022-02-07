<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:06:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\Shop;


use App\Models\Trade\Shop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;
use function data_set;

/**
 * @property Shop $shop
 * @property string $module
 */
class IndexShop
{
    use AsAction;
    use WithInertia;



    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Shop::class)
            ->when($this->get('type'), function ($query, $type) {
                return $query->where(
                    'type',$type

                );
            })
            ->allowedSorts(['code', 'name'])
            ->paginate()
            ->withQueryString();
    }

    public function rules(): array
    {
        return [
            'module' => [
                'required',
                Rule::in(['shops', 'fulfilment_houses']),
            ],


        ];
    }



    public function asInertia($module = false)
    {


        $this->set('module', $module);
        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            'index-model',
            [
                'headerData' => [
                    'module'      => 'shops',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => data_set($breadcrumbs, "index.current", true),

                ],
                'dataTable'  => [
                    'records' => $this->handle(),
                    'columns' => [
                        'code'      => [
                            'sort'  => 'code',
                            'label' => __('Code'),
                            'href'  => [
                                'route'  =>  match ($this->module) {
                                    'fulfilment_houses' => 'fulfilment_houses.show',
                                    default => 'ecommerce_shops.show',
                                },
                                'column' => 'id'
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


        $request->merge(
            [
                'title' => match ($this->module) {
                    'fulfilment_houses' => __('Fulfilment houses'),
                    default => __('Stores'),
                },
                'type'=>match ($this->module) {
                    'fulfilment_houses' => 'fulfilment_house',
                    default => 'shop'
                }

            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs',$this->breadcrumbs());


    }


    private function breadcrumbs(): array
    {

        return [
            'index' => [
                'route'   => $this->module.'.index',
                'name'    => $this->get('title'),
                'current' => false
            ],
        ];
    }

    public function getBreadcrumbs($module): array
    {
        $this->set('module', $module);
        $this->validateAttributes();
        return $this->breadcrumbs();

    }


}
