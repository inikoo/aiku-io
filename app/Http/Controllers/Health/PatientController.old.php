<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 00:42:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Assets\CountrySelectOptionsController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePatientGuardianRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientGuardianAddressRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\UpdatePatientGuardianRequest;
use App\Models\Assets\Country;
use App\Models\Health\Patient;
use App\Http\Controllers\Traits\HasContact;
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
    use HasContact;

    protected array $breadcrumbs = [];
    private string $module;
    private array $guardiansTypes;

    private array $identityDocumentTypes;
    private mixed $defaultCountry;


    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'patients.index',
                'name'    => __('Patients'),
                'current' => false
            ],
        ];


        $this->guardiansTypes = [
            'Mother'   => __('Mother'),
            'Father'   => __('Father'),
            'Guardian' => __('Guardian'),
            'Other'    => __('Other'),
        ];


        $countryID            = app('currentTenant')->cointry_id ?? Country::firstWhere('code', config('app.country'))->id;
        $this->defaultCountry = Country::find($countryID);


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
            ->leftJoin('contacts', 'contacts.id', '=', 'patients.contact_id')
            ->defaultSort('-patients.id')
            ->allowedAppends(['contacts.age', 'patients.formatted_id', 'contacts.formatted_dob'])
            ->allowedSorts(['contacts.name', 'contacts.date_of_birth', 'contacts.gender', 'patients.id'])
            ->allowedFilters(['contacts.name', 'contacts.date_of_birth', 'contacts.gender', $globalSearch])
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
                'route'   => 'patients.create',
                'name'    => __('Patient registration'),
                'current' => true
            ]
        ]);

        $blueprint   = [];
        $blueprint[] = $this->patientTypeBlueprint();
        $blueprint[] = $this->personalInformationBlueprint();
        $blueprint[] = $this->identityDocumentBlueprint();
        $blueprint[] = $this->contactInformationBlueprint(withGuardian: true);

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
                    'blueprint'   => $blueprint,
                    'args'        => [
                        'countriesAddressData' => (new CountrySelectOptionsController())->getCountriesAddressData(),
                    ]
                ]


            ]
        );
    }

    public function store(CreatePatientRequest $request): RedirectResponse
    {
        $patient = Patient::create($request->only('type'));


        if ($request->only('type')['type'] == 'dependant') {
            $contactFields = [
                'name',
                'date_of_birth',
                'gender',
                'identity_document_number',
                'identity_document_type'
            ];
        } else {
            $contactFields = [
                'name',
                'date_of_birth',
                'gender',
                'identity_document_number',
                'identity_document_type',
                'email',
                'phone'
            ];
        }


        $contact = Contact::create(
            $request->only($contactFields)
        );

        if ($other_identity_document_type = $request->only('other_identity_document_type')['other_identity_document_type']) {
            $data = $contact->data;
            data_set($data, 'other_identity_document_type', $other_identity_document_type);
            $contact->data = $data;
            $contact->save();
        }


        $patient->contact_id = $contact->id;
        $patient->save();

        if ($request->only('type')['type'] == 'dependant') {


            $contactData=  $request->only(['email', 'phone']);
            $contactData['name']=$request->only('guardian_name')['guardian_name'];

            $guardian = Contact::create(
                $contactData
            );


            $guardian->address()->create($request->only(['country_id', 'administrative_area', 'dependant_locality', 'locality', 'postal_code', 'sorting_code', 'address_line_2', 'address_line_1']));

            $guardian->dependants()->attach($patient->id, $request->only(['relation']));
        } else {
            $contact->address()->create($request->only(['country_id', 'administrative_area', 'dependant_locality', 'locality', 'postal_code', 'sorting_code', 'address_line_2', 'address_line_1']));
        }


        return Redirect::route('patients.show', ['id' => $patient->id]);
    }

    public function storeGuardian($id, CreatePatientGuardianRequest $request): RedirectResponse
    {
        $patient = Patient::find($id);
        $contact = Contact::create($request->except('relation'));
        $patient->guardians()->attach($contact, $request->only('relation'));

        return Redirect::route('patients.edit', ['id' => $patient->id]);
    }

    public function updateGuardian($id, $guardianId, UpdatePatientGuardianRequest $request): RedirectResponse
    {
        $patient = Patient::find($id);
        $patient->guardians()->updateExistingPivot($guardianId, $request->only('relation'));
        $patient->guardians()->find($guardianId)->update($request->except('relation'));

        return Redirect::route('patients.edit', ['id' => $patient->id]);
    }

    public function updateGuardianAddress($id, $guardianId, UpdatePatientGuardianAddressRequest $request): RedirectResponse
    {
        $patient = Patient::findOrFail($id);
        $patient->guardians()->updateExistingPivot($guardianId, $request->only('relation'));
        /** @var \App\Models\Helpers\Contact $contact * */
        $contact = $patient->guardians()->findOrFail($guardianId);
        $address = $contact->address();
        $address->update($request->all());

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


        $idFormattedName = match ($patient->contact->identity_document_type) {
            'Passport' => __('Passport'),
            'Other' => Arr::get($patient->contact->data, 'other_identity_document_type', __('Unknown ID')),
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
                'type'     => 'contacts',
                'title'    => __('Guardian contact details'),
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


        if ($patient->type == 'dependant') {
            if ($patient->contact->ageInYears > 18) {
                $blueprint[] = $this->patientTypeBlueprint(patient: $patient);
            }


            $blueprint[] = $this->personalInformationBlueprint(patient: $patient);
            $blueprint[] = $this->identityDocumentBlueprint(patient: $patient);

            foreach ($patient->guardians as $guardian) {
                $blueprint[] = $this->guardianBlueprint(owner: [$patient, $guardian]);
            }

            if (count($patient->guardians) == 0) {
                $blueprint[] = [
                    'title'  => __('Guardian'),
                    'fields' => [

                        'newGuardian' => [
                            'type' => 'form',


                            'formData' => [
                                'postURL' => "/patients/$patient->id/guardians/create",

                                'blueprint' => $this->guardianBlueprint()


                            ]
                        ],


                    ]
                ];
            }
        } else {
            $blueprint[] = $this->patientTypeBlueprint(patient: $patient);
            $blueprint[] = $this->personalInformationBlueprint(patient: $patient);
            $blueprint[] = $this->identityDocumentBlueprint(patient: $patient);
            $blueprint[] = $this->contactInformationBlueprint(patient: $patient);
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

                    'blueprint' => $blueprint,
                    'args'      => [
                        'countriesAddressData' => (new CountrySelectOptionsController())->getCountriesAddressData(),
                        'postURL'              => "/patients/$patient->id/edit",
                    ]

                ],

            ]
        );
    }

    public function update(UpdatePatientRequest $request, $id): RedirectResponse
    {
        $patient = Patient::findOrFail($id);

        $patient->update($request->only('type'));


        foreach ($request->only('other_identity_document_type') as $value) {
            $data = $patient->contact->data;
            data_set($data, 'other_identity_document_type', $value);
            $patient->contact->data = $data;
            $patient->contact->save();
        }

        $patient->contact->update($request->except('type'));

        return Redirect::route('patients.edit', $patient->id);
    }

    public function destroy($id)
    {
    }

    private function personalInformationBlueprint($patient = false): array
    {
        $fields = [
            'name'          => [
                'type'  => 'text',
                'label' => __('Name'),
                'value' => $patient ? $patient->contact->name : ''
            ],
            'date_of_birth' => [
                'type'  => 'date',
                'label' => __('Date of birth'),
                'value' => $patient ? $patient->contact->date_of_birth : ''
            ],
            'gender'        => [
                'type'    => 'radio',
                'label'   => __('Gender'),
                'options' => [

                    [
                        'value' => 'male',
                        'name'  => __('Male')
                    ],
                    [
                        'value' => 'female',
                        'name'  => __('Female')
                    ],


                ],
                'value'   => $patient ? $patient->contact->gender : ''
            ],


        ];


        return [

            'title'    => __('Personal information'),
            'subtitle' => '',
            'fields'   => $fields
        ];
    }

    private function contactInformationBlueprint($patient = false, $withGuardian = false): array
    {
        $fields = [];

        if ($withGuardian) {
            $fields['relation']      = [

                'type'        => 'select',
                'options'     => $this->guardiansTypes,
                'label'       => __('Relation'),
                'value'       => '',
                'conditional' => [
                    'if' => ['type', 'dependant']
                ]

            ];
            $fields['guardian_name'] = [
                'type'        => 'text',
                'label'       => __('Guardian name'),
                'value'       => '',
                'conditional' => [
                    'if' => ['type', 'dependant']
                ]
            ];
        }


        $fields['email']   = [
            'type'  => 'text',
            'label' => __('Email'),
            'value' => $patient ? $patient->contact->email : ''
        ];
        $fields['phone']   = [
            'type'               => 'phone',
            'label'              => __('Phone'),
            'defaultCountryCode' => $this->defaultCountry->code,
            'value'              => $patient ? $patient->contact->phone : ''
        ];
        $fields['address'] = [
            'postURL' => $patient ? "/patients/$patient->id/address/edit" : null,
            'type'    => 'address',
            'label'   => __('Address'),
            'value'   => $patient ? $patient->contact->address : ['county_id' => $this->defaultCountry->id]
        ];


        return [

            'title'            => __('Patient contact information'),
            'subtitle'         => '',
            'fields'           => $fields,
            'conditionalTitle' => [
                'title' => __('Guardian contact information'),
                'if'    => ['type', 'dependant']
            ]
        ];
    }

    private function identityDocumentBlueprint($patient = false): array
    {
        $fields = [

            'identity_document_type'   => [
                'type'     => 'radio',
                'label'    => __('Id type'),
                'hasOther' =>
                    [
                        'name'  => 'other_identity_document_type',
                        'value' => $patient ? Arr::get($patient->contact->data, 'other_identity_document_type') : ''
                    ],
                'options'  => $this->identityDocumentTypes,
                'value'    => $patient ? $patient->contact->identity_document_type : ''
            ],
            'identity_document_number' => [
                'type'  => 'text',
                'label' => __('Id number'),
                'value' => $patient ? $patient->contact->identity_document_number : ''
            ],

        ];


        return [

            'title'    => __('Identity document'),
            'subtitle' => '',
            'fields'   => $fields
        ];
    }

    private function patientTypeBlueprint($patient = false): array
    {
        return [
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
                    'value'   => $patient ? $patient->type : ''
                ],
            ]
        ];
    }

    private function guardianBlueprint($owner = false): array
    {
        if ($owner) {
            list($patient, $guardian) = $owner;
        }


        return [
            'title'  => __('Guardian contact details'),
            'fields' => [
                'relation' => [
                    'postURL' => $owner ? "/patients/$patient->id/guardians/$guardian->id/edit" : null,
                    'type'    => 'select',
                    'options' => $this->guardiansTypes,
                    'label'   => __('Relation'),
                    'value'   => $owner ? $guardian->pivot->relation : ''
                ],
                'name'     => [
                    'postURL' => $owner ? "/patients/$patient->id/guardians/$guardian->id/edit" : null,
                    'type'    => 'text',
                    'label'   => __('Name'),
                    'value'   => $owner ? $guardian->name : ''
                ],
                'email'    => [
                    'postURL' => $owner ? "/patients/$patient->id/guardians/$guardian->id/edit" : null,

                    'type'  => 'text',
                    'label' => __('Email'),
                    'value' => $owner ? $guardian->email : ''
                ],
                'phone'    => [
                    'postURL'            => $owner ? "/patients/$patient->id/guardians/$guardian->id/edit" : null,
                    'type'               => 'phone',
                    'defaultCountryCode' => $this->defaultCountry->code,
                    'label'              => __('Phone'),
                    'value'              => $owner ? $guardian->phone : ''
                ],
                'address'  => [
                    'postURL' => $owner ? "/patients/$patient->id/guardians/$guardian->id/address/edit" : null,
                    'type'    => 'address',
                    'label'   => __('Address'),
                    'value'   => $owner ? $guardian->address : ['county_id' => $this->defaultCountry->id]
                ],
            ]
        ];
    }


}
