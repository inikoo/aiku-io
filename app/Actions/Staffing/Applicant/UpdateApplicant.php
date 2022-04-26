<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 24 Sep 2021 12:00:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Staffing\Applicant;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Staffing\Applicant;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateApplicant
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(Applicant $applicant, array $applicantData): ActionResult
    {
        $res = new ActionResult();


        $applicant->update(
            Arr::except($applicantData, [
                'data',
                'salary',
                'working_hours',
                'applicant_relationships',
                'job_positions'

            ])
        );
        $applicant->update($this->extractJson($applicantData, ['data', 'salary', 'working_hours']));

        $res->changes = $applicant->getChanges();

        if (Arr::exists($applicantData, 'job_positions')) {
            $res          = UpdateApplicantJobPositions::run(
                applicant:  $applicant,
                operation: $applicantData['job_positions']['operation'],
                ids:       $applicantData['job_positions']['ids'],

            );
            $res->changes = array_merge($res->changes, $res->changes);
        }

        if (Arr::exists($applicantData, 'applicant_relationships')) {
            $res          = UpdateApplicantRelationships::run(
                applicant:           $applicant,
                type:               $applicantData['applicant_relationships']['type'],
                operation:          $applicantData['applicant_relationships']['operation'],
                relatedApplicantIds: $applicantData['applicant_relationships']['ids'],

            );
            $res->changes = array_merge($res->changes, $res->changes);
        }


        $res->model = $applicant;

        $res->model_id = $applicant->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit') || $request->user()->hasPermissionTo("applicants.edit");
    }

    public function rules(): array
    {
        return [
            'name'                     => 'sometimes|required|string',
            'email'                    => 'sometimes|email',
            'phone'                    => 'sometimes|phone:AUTO',
            'identity_document_number' => 'sometimes|required|string',
            'date_of_birth'            => 'sometimes|nullable|date|before_or_equal:today',
            'address'                  => 'sometimes|nullable|string',
            'emergency_contact'        => 'sometimes|nullable|string',
            'nickname'                 => 'sometimes|required|string',
            'worker_number'            => 'sometimes|required|string',
            'salary'                   => 'sometimes|required|array',
            'working_hours'            => 'sometimes|required|array',
            'applicant_relationships'   => 'sometimes|required|array:type,operation,ids',
            'job_position_slugs'       => 'sometimes|required|array:operation,slugs',

            'status' => [
                'sometimes',
                'required',
                Rule::in(['working', 'ex-applicant', 'hired']),
            ]
        ];
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        if ($request->exists('salary')) {
            $request->merge(
                [
                    'salary' => json_decode($request->get('salary'), true),
                ]
            );
        }
        if ($request->exists('working_hours')) {
            $request->merge(
                [
                    'working_hours' => json_decode($request->get('working_hours'), true)
                ]

            );
        }
        if ($request->exists('applicant_relationships')) {
            $request->merge(
                [
                    'applicant_relationships' => json_decode($request->get('applicant_relationships'), true)
                ]

            );
        }

        if ($request->exists('job_position_slugs')) {
            $request->merge(
                [
                    'job_position_slugs' => json_decode($request->get('job_position_slugs'), true)
                ]

            );
        }
    }


    public function afterValidator(Applicant $applicant, Validator $validator, ActionRequest $request): void
    {
        if ($request->exists('applicant_relationships')) {
            $applicant_relationships = json_decode($request->get('applicant_relationships'), true);

            foreach ($applicant_relationships['ids'] as $id) {
                $relatedApplicant = Applicant::find($id);
                if ($relatedApplicant and $applicant->id == $relatedApplicant->id) {
                    $validator->errors()->add('applicant_relationships', 'Related applicant same as applicant.');
                } else {
                    $validator->errors()->add('applicant_relationships', 'Related applicant not found.');
                }
            }
        }

        if ($request->exists('job_position_slugs')) {
            $jobPositions = [];

            $job_position_slugs = json_decode($request->get('job_position_slugs'), true);

            foreach ($job_position_slugs['slugs'] as $slug) {
                $jobPosition = JobPosition::firstWhere('slug', $slug);
                if ($jobPosition) {
                    $jobPositions[] = $jobPosition->id;
                } else {
                    $validator->errors()->add('job_positions', 'Wrong job position slug.');
                }
            }

            $request->merge(
                [
                    'job_positions' =>
                        [
                            'operation' => $job_position_slugs['operation'],
                            'ids'       => $jobPositions
                        ]
                ]
            );
        }
    }


    public function asController(Applicant $applicant, ActionRequest $request): ActionResultResource
    {
        $request->validate();

        $modelData = $request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'date_of_birth',
            'nickname',
            'worker_number',
            'emergency_contact',
            'salary',
            'job_title',
            'working_hours',
            'applicant_relationships',
            'job_positions'

        );
        $data      = $request->only('address');
        if ($data) {
            $modelData['data'] = $data;
        }


        return new ActionResultResource(
            $this->handle(
                $applicant,
                $modelData
            )
        );
    }

    public function asInertia(Applicant $applicant, Request $request): RedirectResponse
    {
        $this->set('applicant', $applicant);
        $this->fillFromRequest($request);
        $this->validateAttributes();


        $modelData = $request->only(
            'name',
            'email',
            'phone',
            'identity_document_number',
            'date_of_birth',
            'nickname',
            'worker_number',
            'emergency_contact',
            'salary',
            'job_title',
            'working_hours',
            'applicant_relationships',
            'job_positions'

        );
        $data      = $request->only('address');
        if ($data) {
            $modelData['data'] = $data;
        }

        $this->handle(
            $applicant,
            $modelData

        );

        return Redirect::route('human_resources.applicants.edit', $applicant->id);
    }

}
