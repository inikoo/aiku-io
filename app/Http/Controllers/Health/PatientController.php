<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 00:42:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePatientGuardianRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\UpdatePatientGuardianRequest;
use App\Models\Health\Patient;
use App\Models\Helpers\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
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
            ->allowedIncludes(['contact'])
            ->select('contacts.*', 'patients.*')
            ->rightJoin('contacts', 'contacts.id', '=', 'patients.contact_id')
            ->defaultSort('-patients.id')
            ->allowedAppends(['contacts.age', 'patients.formatted_id', 'contacts.formatted_dob'])
            ->allowedSorts(['contacts.name', 'contacts.date_of_birth', 'contacts.gender', 'patients.id'])
            ->allowedFilters(['contacts.name', 'contacts.date_of_birth', 'contacts.gender', $globalSearch])
            ->paginate()
            ->withQueryString();

        //dd($patients);

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

    public function storeGuardian($id, CreatePatientGuardianRequest $request): RedirectResponse
    {
        $patient = Patient::find($id);
        $contact = Contact::create($request->except('relation'));
        $patient->guardians()->attach($contact, $request->only('relation'));

        return Redirect::route('patients.edit', ['id' => $patient->id]);
    }

    public function editGuardian($id, $guardianId, UpdatePatientGuardianRequest $request): RedirectResponse
    {
        $patient = Patient::find($id);


        $patient->guardians()->updateExistingPivot($guardianId, $request->only('relation'));

        $patient->guardians()->find($guardianId)->update($request->except('relation'));


        return Redirect::route('patients.edit', ['id' => $patient->id]);
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


        $idFormattedName= match ($patient->contact->identity_document_type) {
            'Passport' => __('Passport'),
            'Other' => Arr::get($patient->contact->data, 'other_identity_document_type',__('Unknown ID')),
            default => $patient->contact->identity_document_type,
        };



        $blueprint   = [];
        $blueprint[] = [
            'type' => 'two_columns',
            'data' => [
                ['title' => $idFormattedName, 'value' => $patient->contact->identity_document_number],
                ['title' => __('Date of birth'), 'value' => $patient->contact->formatted_dob]
            ]
        ];
        if ($patient->type == 'adult') {
            $blueprint[] = [
                'type' => 'two_columns',
                'data' => [
                    ['title' => __('Email'), 'value' => $patient->contact->email],
                    ['title' => __('Phone'), 'value' => $patient->contact->phone]
                ]
            ];

            $blueprint[] = [
                'type' => 'two_columns',
                'data' => [
                    ['title' => __('Address'), 'value' => $patient->contact->formatted_address],
                    ['title' => '', 'value' => '']
                ]
            ];
        }



        if (count($patient->guardians) > 0) {
            $blueprint[] = [
                'type'      => 'contacts',
                'title'     => __('Guardian contact details'),
                'contacts' => $patient->guardians
            ];
        }


        return Inertia::render(
            'Patients/Patient',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => $patient->contact->name,
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
                            'name' => $patient->contact->formatted_dob.' ('.$patient->contact->getAgeAttribute('verbose').')'
                        ],
                        [
                            'icon' => $patient->contact->gender_icon,
                            'name' => $patient->contact->formatted_gender
                        ],
                    ]
                ],
                'patient'    => $patient,
                'cardData'   =>
                    [
                        'title'     => __('Patient personal information'),
                        'subTitle'  => '',
                        'blueprint' => $blueprint
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

        $blueprint = [];


        $patientType = [
            'title'    => __('Patient type'),
            'subtitle' => '',
            'fields'   => [

                'type' => [
                    'type'    => 'radio',
                    'label'   => __('Type'),
                    'options' => [

                        [
                            'value' => 'dependant',
                            'name'  => __('Dependent')
                        ],
                        [
                            'value' => 'adult',
                            'name'  => __('Adult')
                        ],


                    ],
                    'value'   => $patient->type
                ],
            ]
        ];

        $nationalIdBlueprint = [
            'title'    => __('Id'),
            'subtitle' => '',
            'fields'   => [

                'identity_document_type'   => [
                    'type'     => 'radio',
                    'label'    => __('Id type'),
                    'hasOther' =>
                        [
                            'name'  => 'other_identity_document_type',
                            'value' => Arr::get($patient->contact->data, 'other_identity_document_type')
                        ],
                    'options'  => [

                        [
                            'value' => 'MyKad',
                            'name'  => 'MyKad'
                        ],
                        [
                            'value' => 'Passport',
                            'name'  => __('Passport')
                        ],
                        [
                            'value'   => 'Other',
                            'name'    => __('Other'),
                            'isOther' => true
                        ],


                    ],
                    'value'    => $patient->contact->identity_document_type
                ],
                'identity_document_number' => [
                    'type'  => 'text',
                    'label' => __('Id number'),
                    'value' => $patient->contact->identity_document_number
                ],
            ]
        ];

        if ($patient->type == 'dependant') {
            if ($patient->contact->ageInYears() > 18) {
                $blueprint[] = $patientType;
            }


            $personalInformationBlueprint = [
                'title'    => __('Personal information'),
                'subtitle' => '',
                'fields'   => [
                    'name'          => [
                        'type'  => 'text',
                        'label' => __('Name'),
                        'value' => $patient->contact->name
                    ],
                    'date_of_birth' => [
                        'type'  => 'date',
                        'label' => __('Date of birth'),
                        'value' => $patient->contact->date_of_birth
                    ],
                    'gender'        => [
                        'type'    => 'radio',
                        'label'   => __('Gender'),
                        'options' => [

                            [
                                'value' => 'Male',
                                'name'  => __('Male')
                            ],
                            [
                                'value' => 'Female',
                                'name'  => __('Female')
                            ],


                        ],
                        'value'   => $patient->contact->gender
                    ],

                ]
            ];


            $blueprint[] = $personalInformationBlueprint;
            $blueprint[] = $nationalIdBlueprint;

            foreach ($patient->guardians as $guardian) {
                $blueprint[] = [
                    'title'  => __('Guardian'),
                    'fields' => [
                        'relation' => [
                            'postURL' => "/patients/$patient->id/guardians/$guardian->id/edit",
                            'type'    => 'select',
                            'options' => [
                                'Mother'   => __('Mother'),
                                'Father'   => __('Father'),
                                'Guardian' => __('Guardian'),
                                'Other'    => __('Other'),
                            ],
                            'label'   => __('Relation'),
                            'value'   => $guardian->pivot->relation
                        ],
                        'name'     => [
                            'postURL' => "/patients/$patient->id/guardians/$guardian->id/edit",
                            'type'    => 'text',
                            'label'   => __('Name'),
                            'value'   => $guardian->name
                        ],
                        'email'    => [
                            'postURL' => "/patients/$patient->id/guardians/$guardian->id/edit",

                            'type'  => 'text',
                            'label' => __('Email'),
                            'value' => $guardian->email
                        ],
                        'phone'    => [
                            'postURL' => "/patients/$patient->id/guardians/$guardian->id/edit",

                            'type'  => 'text',
                            'label' => __('Phone'),
                            'value' => $guardian->phone
                        ],
                    ]
                ];
            }

            if (count($patient->guardians) == 0) {
                $blueprint[] = [
                    'title'  => __('Guardian'),
                    'fields' => [

                        'newGuardian' => [
                            'type' => 'form',


                            'formData' => [
                                'postURL' => "/patients/$patient->id/guardians/create",

                                'blueprint' => [
                                    [

                                        'title'       => __('Guardian contact details'),
                                        'subtitle'    => '',
                                        'cancelRoute' => null,
                                        'fields'      => [
                                            'relation' => [
                                                'type'    => 'select',
                                                'options' => [
                                                    'Mother'   => __('Mother'),
                                                    'Father'   => __('Father'),
                                                    'Guardian' => __('Guardian'),
                                                    'Other'    => __('Other'),
                                                ],
                                                'label'   => __('Relation'),
                                                'value'   => ''
                                            ],
                                            'name'     => [

                                                'type'  => 'text',
                                                'label' => __('Name'),
                                                'value' => ''
                                            ],
                                            'email'    => [
                                                'type'  => 'text',
                                                'label' => __('Email'),
                                                'value' => ''
                                            ],
                                            'phone'    => [
                                                'type'  => 'text',
                                                'label' => __('Phone'),
                                                'value' => ''
                                            ],
                                        ]
                                    ]
                                ],

                            ]
                        ],


                    ]
                ];
            }
        } else {
            $blueprint[] = $patientType;

            $personalInformationBlueprint = [
                'title'    => __('Personal information'),
                'subtitle' => '',
                'fields'   => [
                    'name'          => [
                        'type'  => 'text',
                        'label' => __('Name'),
                        'value' => $patient->contact->name
                    ],
                    'date_of_birth' => [
                        'type'  => 'date',
                        'label' => __('Date of birth'),
                        'value' => $patient->contact->date_of_birth
                    ],
                    'gender'        => [
                        'type'    => 'radio',
                        'label'   => __('Gender'),
                        'options' => [

                            [
                                'value' => 'Male',
                                'name'  => __('Male')
                            ],
                            [
                                'value' => 'Female',
                                'name'  => __('Female')
                            ],


                        ],
                        'value'   => $patient->contact->gender
                    ],
                    'email'         => [
                        'type'  => 'text',
                        'label' => __('Email'),
                        'value' => $patient->contact->email
                    ],
                    'phone'         => [
                        'type'  => 'text',
                        'label' => __('Phone'),
                        'value' => $patient->contact->phone
                    ],
                ]
            ];

            $blueprint[] = $personalInformationBlueprint;
            $blueprint[] = $nationalIdBlueprint;
        }


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
                    'blueprint' => $blueprint

                ],

            ]
        );
    }


    public function update(UpdatePatientRequest $request, $id): RedirectResponse
    {
        $patient = Patient::findOrFail($id);

        $patient->update($request->only('type'));


        foreach ($request->only('other_identity_document_type') as $value) {

            $data=$patient->contact->data;
            data_set($data,'other_identity_document_type',$value);
            $patient->contact->data=$data;
            $patient->contact->save();

        }

        $patient->contact->update($request->except('type'));

        return Redirect::route('patients.edit', $patient->id);
    }


    public function destroy($id)
    {
    }
}
