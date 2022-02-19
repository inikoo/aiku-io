<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Sep 2021 02:39:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Employee;

use App\Actions\Account\Tenant\ShowTenant;
use App\Actions\HumanResources\ShowHumanResourcesDashboard;
use App\Actions\UI\WithInertia;
use App\Http\Resources\HumanResources\EmployeeInertiaResource;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property array $breadcrumbs
 * @property bool $canEdit
 * @property string $title
 */
class IndexEmployee
{
    use AsAction;
    use WithInertia;

    public function handle(): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('employees.name', 'LIKE', "%$value%")
                    ->orWhere('employees.nickname', 'LIKE', "%$value%");
            });
        });


        return QueryBuilder::for(Employee::class)
            ->defaultSort('-employees.id')
            ->select(['nickname', 'id', 'worker_number','name'])

            ->allowedSorts(['nickname', 'worker_number', 'name'])
            ->allowedFilters([$globalSearch])
            ->paginate()
            ->withQueryString();
    }

    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('employees.view')
            );
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        $employees = QueryBuilder::for(Employee::class)
            ->allowedFilters(['nickname', 'worker_number', 'state'])
            ->paginate();

        return EmployeeResource::collection($employees);
    }

    public function asInertia()
    {
        $this->validateAttributes();


        $actionIcons = [];


        /*
        $actionIcons['human_resources.employees.logbook'] =[
           'name' => __('History'),
           'icon' => ['fal', 'history']
        ];
        */

        if ($this->canEdit) {
            $actionIcons['human_resources.employees.create'] = [
                'name' => __('Create employee'),
                'icon' => ['fal', 'plus']
            ];
        }


        return Inertia::render(
            'index-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'navData' => ['module' => 'human_resources', 'sectionRoot' => 'human_resources.employees.index'],

                'headerData' => [
                    'title'       => $this->title,

                    'actionIcons' => $actionIcons,
                ],
                'dataTable'  => [
                    'records' => EmployeeInertiaResource::collection($this->handle()),
                    'columns' => [
                        'nickname'      => [
                            'sort'  => 'nickname',
                            'label' => __('Nickname'),
                            'href'  => [
                                'route'  => 'human_resources.employees.show',
                                'column' => 'id'
                            ],
                        ],
                        'worker_number' => [
                            'sort'  => 'worker_number',
                            'label' => __('Worker #')
                        ],
                        'name'          => [
                            'sort'         => 'name',
                            'label'        => __('Name')
                        ],
                    ]
                ]


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [
                    'contacts.name'      => __('Name'),
                    'employees.nickname' => __('Nickname'),

                ]
            )->addFilter('employees.state', __('State'), [

                'working' => __('Working'),
                'hired'   => __('Hired'),
                'left'    => __('Left'),
            ]);
        });
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Employees'),

            ]
        );
        $this->fillFromRequest($request);

        $this->set(
            'canEdit',
            ($request->user()->can('employees.edit'))
        );

    }



    public function getBreadcrumbs(): array
    {

        return array_merge(
            (new ShowHumanResourcesDashboard())->getBreadcrumbs(),
            [
                'human_resources.employees.index' => [
                    'route'   => 'human_resources.employees.index',
                    'modelLabel'=>[
                        'label'=>__('employees')
                    ],
                ],
            ]
        );
    }


}
