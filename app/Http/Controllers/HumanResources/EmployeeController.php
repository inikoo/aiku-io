<?php

namespace App\Http\Controllers\HumanResources;


use App\Http\Controllers\Assets\CountrySelectOptionsController;
use App\Models\HumanResources\Employee;
use App\Http\Controllers\Traits\HasContact;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeController extends HumanResourcesController
{
    use HasContact;

    private array $identityDocumentTypes;
    private mixed $defaultCountry;


    public function __construct()
    {
        parent::__construct();


        $this->defaultCountry        = $this->getDefaultCountry();
        $this->identityDocumentTypes = $this->getDefaultDocumentTypes($this->defaultCountry);

        if (Arr::get($this->defaultCountry->data, 'identity_document_type')) {
            $this->identityDocumentTypes = array_merge(
                Arr::get($this->defaultCountry->data, 'identity_document_type'),
                [
                    [
                        'value' => 'Passport',
                        'name'  => __('Passport')
                    ],
                    [
                        'value'   => 'Other',
                        'name'    => __('Other'),
                        'isOther' => true
                    ]
                ]
            );
        }
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

    public function create(): Response
    {
        $breadcrumbs = array_merge($this->breadcrumbs, [
            'users' => [
                'route'   => 'employees.create',
                'name'    => __('Employee registration'),
                'current' => true
            ]
        ]);

        $blueprint   = [];
        $blueprint[] = $this->employeeTypeBlueprint();
        $blueprint[] = $this->personalInformationBlueprint();
        $blueprint[] = $this->identityDocumentBlueprint();
        $blueprint[] = $this->contactInformationBlueprint(withGuardian: true);

        return Inertia::render(
            'Employees/NewEmployee',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('New employee'),
                    'breadcrumbs' => $breadcrumbs,
                    'actionIcons' => [
                        'employees.index' => [
                            'name' => __('Cancel'),
                            'icon' => ['fal', 'portal-exit'],
                        ]
                    ],
                ],

                'formData' => [
                    'postURL'     => '/employees/create',
                    'cancelRoute' => ['employees.index'],
                    'blueprint'   => $blueprint,
                    'args'        => [
                        'countriesAddressData' => (new CountrySelectOptionsController())->getCountriesAddressData(),
                    ]
                ]


            ]
        );
    }
}
