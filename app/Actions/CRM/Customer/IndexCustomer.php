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
 */
class IndexCustomer
{
    use AsAction;
    use WithInertia;


    protected array $select;
    protected array $columns;
    protected array $allowedSorts;

    public function __construct()
    {
        $this->select       = ['id', 'name', 'shop_id'];
        $this->allowedSorts = ['name', 'id', 'location'];

        $this->columns = [


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
                                    'route'   => 'marketing.shops.show.customers.index',
                                    'indices' => 'shop_id'
                                ],
                                'indices' => 'shop_code'
                            ],


                        ]
                    ]
                ],

            ],

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
                                    'route'   => 'marketing.shops.show.customers.show',
                                    'indices' => ['shop_id', 'id']
                                ],
                                'indices' => 'customer_number'
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
        return QueryBuilder::for(Customer::class)
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
                'navData'     => ['module' => 'shops', 'metaSection' => $this->metaSection, 'sectionRoot' => $this->sectionRoot],
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
        return CustomerInertiaResource::collection($this->handle());
    }

}
