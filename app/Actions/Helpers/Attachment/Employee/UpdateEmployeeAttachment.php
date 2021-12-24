<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:47:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment\Employee;

use App\Actions\Helpers\Attachment\Traits\HasValidateModelAttachment;
use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Validator;

class UpdateEmployeeAttachment
{
    use AsAction;
    use WithUpdate;

    use HasValidateModelAttachment;

    public function handle(Attachment $attachment, array $attachmentData): ActionResult
    {
        $res = new ActionResult();

        $attachment->update($attachmentData);
        $res->model    = $attachment;
        $res->changes  = $attachment->getChanges();
        $res->model_id = $attachment->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit');
    }

    public function rules(): array
    {
        return [

            'caption' => 'sometimes|nullable|string',
            'public'  => 'sometimes|nullable|boolean',
            'scope'   => [
                'sometimes',
                'required',
                Rule::in(['cv', 'contract', 'other']),
            ],
            'aurora_id'  => 'sometimes|nullable|integer',

        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        /** @var Employee $employee */
        /** @var Attachment $attachment */

        $employee = $request->route('employee');

        $attachment = $request->route('attachment');

        if (!$this->validateModelAttachment(model: $employee, attachment: $attachment)) {
            $validator->errors()->add('attachment_mode', 'Attachment do not belongs to Model.');
        }
    }


    /** @noinspection PhpUnusedParameterInspection */
    public function asController(Employee $employee, Attachment $attachment, ActionRequest $request): ActionResultResource
    {
        return new ActionResultResource(
            $this->handle(

                $attachment,
                $request->only(
                    'caption',
                    'scope',
                    'public',
                )
            )
        );
    }

}
