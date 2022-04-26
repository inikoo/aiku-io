<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 14:39:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Staffing\Applicant;


use App\Http\Resources\CRM\CustomerInertiaResource;

use App\Http\Resources\Staffing\ApplicantInertiaResource;
use App\Models\CRM\Customer;
use App\Models\Staffing\Applicant;
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
class IndexApplicant
{
    use AsAction;
    use WithInertia;


    protected array $select;
    protected array $columns;
    protected array $allowedSorts;

    public function __construct()
    {
        $this->select       = ['nickname', 'id', 'worker_number', 'name'];
        $this->allowedSorts = ['nickname', 'worker_number', 'name'];

        $this->columns =  [


            [
                'sort'       => 'code',
                'label' => __('Code'),
                'components' => [
                    [
                        'type'     => 'link',
                        'resolver' => [
                            'type' => 'link',

                            'parameters' => [
                                'href'    => [
                                    'route'  => 'human_resources.employees.show',
                                    'indices' => 'id'
                                ],
                                'indices' => 'nickname'
                            ],


                        ]
                    ]
                ],

            ],



            [
                'sort'  => 'worker_number',
                'label' => __('Worker #'),
                'resolver'  => 'worker_number',

            ],

            [
                'sort'  => 'name',
                'label' => __('Name'),
                'resolver'=>'name'
            ],
        ];

    }

    public function queryConditions($query)
    {
        return $query->select($this->select);
    }

    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Applicant::class)
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
        return ApplicantInertiaResource::collection($this->handle());
    }

}
