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

class PatientController extends Controller
{

    protected array $breadcrumbs = [];

    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'patients.index',
                'name'    => __('Patients'),
                'current' => false
            ],
        ];
    }


    public function index(): Response
    {
        return Inertia::render(
            'Patients/Patients',
            [
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
                ]
            ]
        );
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
                'title'         => __('New patient'),
                'breadcrumbs'   => $breadcrumbs,
                'formBlueprint' => [
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
            'users' => [
                'route'            => 'patients.show',
                'route_parameters' => $patient->id,
                'name'             => __('Patient'),
                'current'          => true
            ]
        ]);

        return Inertia::render(
            'Patients/Patient',
            [
                'title'       => $patient->name,
                'breadcrumbs' => $breadcrumbs,
                'actionIcons' => [
                    'patients.show.logbook' => [
                        'route_parameters' => $patient->id,
                        'name'             => __('History'),
                        'icon'             => ['fal', 'history']
                    ],
                    'patients.edit'         => [
                        'route_parameters' => $patient->id,
                        'name'             => __('Edit'),
                        'icon'             => ['fal', 'edit']
                    ],
                ]
            ]
        );
    }


    public function edit($id): Response
    {
        $patient = Patient::findOrFail($id);

        $breadcrumbs = array_merge($this->breadcrumbs, [
            'users' => [
                'route'            => 'patients.show',
                'route_parameters' => $patient->id,
                'name'             => __('Patient'),
                'current'          => true
            ]
        ]);

        return Inertia::render(
            'Patients/EditPatient',
            [
                'title'       => __('Editing patient', ['name' => $patient->name]),
                'breadcrumbs' => $breadcrumbs,
                'postURL'     => "/patients/$patient->id/edit",

                'formBlueprint' => [
                    'profile' => [
                        'title'    => __('Personal information'),
                        'subtitle' => '',
                        'fields'   => [
                            'name' => [
                                'type'  => 'text',
                                'label' => __('Name'),
                                'value' => $patient->name
                            ]
                        ]
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
