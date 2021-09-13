<?php

namespace App\Http\Controllers\HumanResources;


use App\Models\HumanResources\Employee;
use Inertia\Inertia;
use Inertia\Response;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeesController extends HumanResourcesController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): Response
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%$value%");
            });
        });


        $employees = QueryBuilder::for(Employee::class)
            ->allowedIncludes(['contact'])
            ->select('contacts.*', 'employees.*')
            ->leftJoin('contacts', 'contacts.id', '=', 'employees.contact_id')
            ->defaultSort('-employees.id')
            ->allowedAppends(['contacts.age', 'employees.formatted_id', 'contacts.formatted_dob'])
            ->allowedSorts(['contacts.name', 'contacts.date_of_birth', 'contacts.gender', 'employees.id'])
            ->allowedFilters(['contacts.name', 'contacts.date_of_birth', 'contacts.gender', $globalSearch])
            ->paginate()
            ->withQueryString();


        return Inertia::render(
            'HumanResources/Employees',
            [

                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('Employees'),
                    'breadcrumbs' => data_set($this->breadcrumbs, "index.current", true),
                    'actionIcons' => [

                        'human_resources.employees.logbook' => [
                            'name' => __('History'),
                            'icon' => ['fal', 'history']
                        ],
                        'human_resources.employees.create'  => [
                            'name' => __('Create employee'),
                            'icon' => ['fal', 'plus']
                        ],
                    ],
                ],


                'employees' => $employees,

            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [
                    'name' => 'Name',

                ]
            )->addFilter('gender', 'gender', [
                'male'   => __('Male'),
                'female' => __('Female'),
            ])->addColumns([
                               'gender'        => 'Gender',
                               'date_of_birth' => 'Date of birth',
                               'age'           => 'Age',
                           ]);
        });
    }
}
