<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 23 Dec 2021 00:47:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\AttachmentModel\Employee;

use App\Actions\Helpers\AttachmentModel\Traits\HasValidateModelAttachment;
use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Helpers\AttachmentModel;
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

    public function handle(AttachmentModel $attachmentModel, array $attachmentModelData): ActionResult
    {
        $res = new ActionResult();

        $attachmentModel->update($attachmentModelData);
        $res->model    = $attachmentModel;
        $res->changes  = $attachmentModel->getChanges();
        $res->model_id = $attachmentModel->id;
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
            ]

        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        /** @var Employee $employee */
        /** @var AttachmentModel $attachmentModel */

        $employee = $request->route('employee');

        $attachmentModel = $request->route('attachmentModel');

        if (!$this->validateModelAttachmentModel(model: $employee, attachmentModel: $attachmentModel)) {
            $validator->errors()->add('attachment_mode', 'AttachmentModel do not belongs to Model.');
        }
    }


    /** @noinspection PhpUnusedParameterInspection */
    public function asController(Employee $employee, AttachmentModel $attachmentModel, ActionRequest $request): ActionResultResource
    {
        return new ActionResultResource(
            $this->handle(

                $attachmentModel,
                $request->only(
                    'caption',


                )
            )
        );
    }

}
