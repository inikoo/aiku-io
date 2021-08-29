<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 00:42:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientController;
use App\Models\Health\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

use Inertia\Response;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PatientController extends Controller
{

    protected array $breadcrumbs = [];
    private string $module;

    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'patients.index',
                'name'    => __('Patients'),
                'current' => false
            ],
        ];

        $this->module = 'patients';
    }


    public function index(): Response
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%$value%");
            });
        });

        $patients = QueryBuilder::for(Patient::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'date_of_birth', 'gender', 'id'])
            ->allowedAppends(['age', 'formatted_id'])
            ->allowedFilters(['name', 'date_of_birth', 'gender', $globalSearch])
            ->paginate()
            ->withQueryString();


        return Inertia::render(
            'Patients/Patients',
            [

                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('Patients'),
                    'breadcrumbs' => data_set($this->breadcrumbs, "index.current", true),
                    'actionIcons' => [

                        'patients.logbook' => [
                            'name' => __('History'),
                            'icon' => ['fal', 'history']
                        ],
                        'patients.create'  => [
                            'name' => __('Create patient'),
                            'icon' => ['fal', 'plus']
                        ],
                    ],
                ],


                'patients' => $patients,

            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [
                    'name' => 'Name',

                ]
            )->addFilter('gender', 'gender', [
                'Male'   => __('Male'),
                'Female' => __('Female'),
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
                'route'   => 'patients.create',
                'name'    => __('New patient'),
                'current' => true
            ]
        ]);

        return Inertia::render(
            'Patients/NewPatient',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('New patient'),
                    'breadcrumbs' => $breadcrumbs,
                    'actionIcons' => [
                        'patients.index' => [
                            'name' => __('Cancel'),
                            'icon' => ['fal', 'portal-exit'],
                        ]
                    ],
                ],

                'formData' => [
                    'postURL'     => '/patients/create',
                    'cancelRoute' => ['patients.index'],
                    'blueprint'   => [
                        'profile' => [
                            'title'    => __('Personal information'),
                            'subtitle' => '',
                            'fields'   => [
                                'name' => [
                                    'type'  => 'text',
                                    'label' => __('Name'),
                                    'value' => ''
                                ]
                            ]
                        ],
                    ],
                ]


            ]
        );
    }

    public function store(CreatePatientRequest $request): RedirectResponse
    {
        $patient = Patient::create($request->all());

        return Redirect::route('patients.show', ['id' => $patient->id]);
    }


    public function show($id): Response
    {
        $patient = Patient::find($id);

        $breadcrumbs = array_merge($this->breadcrumbs, [
            'patients' => [
                'route'           => 'patients.show',
                'routeParameters' => $patient->id,
                'name'            => __('Patient').' '.$patient->formatted_id,
                'current'         => true
            ]
        ]);





        return Inertia::render(
            'Patients/Patient',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => $patient->name,
                    'breadcrumbs' => $breadcrumbs,
                    'actionIcons' => [
                        'patients.show.logbook' => [
                            'routeParameters' => $patient->id,
                            'name'            => __('History'),
                            'icon'            => ['fal', 'history']
                        ],
                        'patients.edit'         => [
                            'routeParameters' => $patient->id,
                            'name'            => __('Edit'),
                            'icon'            => ['fal', 'edit']
                        ],
                    ],
                    'meta'        => [
                        [
                            'icon' => ['far', 'birthday-cake'],
                            'name' => $patient->formatted_dob.' ('.$patient->getAgeAttribute('verbose').')'
                        ],
                        [
                            'icon' => $patient->gender_icon,
                            'name' => $patient->formatted_gender
                        ],
                    ]
                ],
                'patient'    => $patient,
                'cardData'   =>
                    [
                        'title'     => __('Patient personal information'),
                        'subTitle'  => '',
                        'blueprint' => [
                            [
                                'type' => 'two_columns',
                                'data' => [
                                    ['title' => __('Full name'), 'value' => $patient->name],
                                    ['title' => __('Date of birth'), 'value' => $patient->formatted_dob]
                                ]
                            ],
                            [
                                'type' => 'contacts',
                                'title'=>__('Contact details'),
                                'contacts' => $patient->contacts
                            ]
                        ]
                    ]

            ]
        );
    }


    public function edit($id): Response
    {
        $patient = Patient::findOrFail($id);

        $breadcrumbs = array_merge($this->breadcrumbs, [
            'show' => [
                'route'           => 'patients.show',
                'routeParameters' => $patient->id,
                'name'            => __('Patient').' '.$patient->formatted_id,
                'current'         => false
            ],
            'edit' => [
                'route'           => 'patients.edit',
                'routeParameters' => $patient->id,
                'name'            => __('Edit'),
                'current'         => true
            ]
        ]);

        return Inertia::render(
            'Patients/EditPatient',
            [


                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('Editing patient').' '.$patient->formatted_id,
                    'breadcrumbs' => $breadcrumbs,
                    'actionIcons' => [
                        'patients.show' => [
                            'routeParameters' => $patient->id,
                            'name'            => __('Exit edit'),
                            'icon'            => ['fal', 'portal-exit'],
                        ]
                    ],

                ],


                'formData' => [
                    'postURL'   => "/patients/$patient->id/edit",
                    'blueprint' => [
                        'profile' => [
                            'title'    => __('Personal information'),
                            'subtitle' => '',
                            'fields'   => [
                                'name' => [
                                    'type'  => 'text',
                                    'label' => __('Name'),
                                    'value' => $patient->name
                                ],
                                'date_of_birth' => [
                                    'type'  => 'date',
                                    'label' => __('Date of birth'),
                                    'value' => $patient->date_of_birth
                                ],
                              
                            ]
                        ],
                    ],

                ],

            ]
        );
    }


    public function update(UpdatePatientController $request, $id): RedirectResponse
    {
        $patient = Patient::findOrFail($id);
        $patient->update($request->all());

        return Redirect::route('patients.edit', $patient->id);
    }


    public function destroy($id)
    {
    }
}
