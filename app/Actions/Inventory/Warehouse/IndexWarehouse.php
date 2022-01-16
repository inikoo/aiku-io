<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:06:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Warehouse;


use App\Models\Inventory\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;
use function data_set;

/**
 * @property Warehouse $warehouse
 */
class IndexWarehouse
{
    use AsAction;
    use WithInertia;



    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Warehouse::class)
            ->when($this->get('type'), function ($query, $type) {
                return $query->where(
                    'type',$type

                );
            })
            ->allowedSorts(['code', 'name'])
            ->paginate()
            ->withQueryString();
    }



    public function asInertia()
    {


        $this->validateAttributes();

        $breadcrumbs = $this->get('breadcrumbs');

        return Inertia::render(
            $this->get('page'),
            [
                'headerData' => [
                    'module'      => 'warehouses',
                    'title'       => $this->get('title'),
                    'breadcrumbs' => data_set($breadcrumbs, "index.current", true),

                ],
                'warehouses'      => $this->handle(),


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
                'page'  =>'Warehouses/Warehouses',
                'title' => __('Warehouses'),


            ]
        );
        $this->fillFromRequest($request);

        $this->set('breadcrumbs',$this->breadcrumbs());


    }


    private function breadcrumbs(): array
    {

        return [
            'index' => [
                'route'   => 'warehouses.index',
                'name'    => $this->get('title'),
                'current' => false
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        $this->validateAttributes();
        return $this->breadcrumbs();

    }


}
