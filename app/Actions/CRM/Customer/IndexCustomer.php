<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Feb 2022 15:33:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Http\Resources\CRM\CustomerInertiaResource;

use App\Models\CRM\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
 */
class IndexCustomer
{
    use AsAction;
    use WithInertia;


    protected array $select;
    protected array $columns;

    public function  __construct()
    {

        $this->select=['id', 'name', 'shop_id'];

        $this->columns=[
            'shop_code' => [
                'label' => __('Shop'),
                'href'  => [
                    'route'  => 'marketing.shops.show.customers.index',
                    'column' => 'shop_id'
                ],
            ],
            'customer_number' => [
                'sort'  => 'customer_number',
                'label' => __('Id'),
                'href'  => [
                    'route'  => 'marketing.shops.show.customers.show',
                    'column' => ['shop_id', 'id']
                ],
            ],
            'name'            => [
                'sort'  => 'name',
                'label' => __('Name')
            ]
        ];

    }

    public function queryConditions($query){
        return $query->select($this->select);
    }

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Customer::class)

            ->when(true, [$this,'queryConditions'])
            ->defaultSorts('-id')
            ->allowedSorts(['name', 'id','location'])
            ->paginate()
            ->withQueryString();
    }



    public function getInertia(){

        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->breadcrumbs,
                'navData' => ['module' => 'shops', 'metaSection' => $this->metaSection,'sectionRoot'=>$this->sectionRoot],

                'headerData' => [
                   'title'=>$this->title

                ],
                'dataTable'  => [
                    'records' => CustomerInertiaResource::collection($this->handle()),
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
