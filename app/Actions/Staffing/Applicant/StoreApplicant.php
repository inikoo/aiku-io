<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Apr 2022 16:31:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Staffing\Applicant;


use App\Models\HumanResources\Workplace;
use App\Models\Staffing\Applicant;
use App\Models\Utils\ActionResult;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreApplicant
{
    use AsAction;


    public function handle(?Workplace $workplace, array $applicantData): ActionResult
    {
        $res = new ActionResult();


        /** @var Applicant $applicant */
        if ($workplace) {

            $applicant = $workplace->applicants()->create($applicantData);
        } else {
            $applicant = Applicant::create($applicantData);
        }


        $applicant->save();

        $res->model    = $applicant;
        $res->model_id = $applicant->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('applicant:store');
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string',
            'email'  => 'email',
            'phone'  => 'phone:AUTO',
            'status' => [
                'required',
                Rule::in(['working', 'ex-applicant', 'hired']),
            ]
        ];
    }

    public function asController(ActionRequest $request): ActionResult
    {
        return $this->handle(
            workplace:    null,
            applicantData: $request->only('status','name', 'email', 'phone')
        );
    }
}
