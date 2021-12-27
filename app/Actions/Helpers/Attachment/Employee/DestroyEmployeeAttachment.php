<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 24 Dec 2021 22:45:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment\Employee;

use App\Actions\Helpers\Attachment\Traits\HasValidateModelAttachment;
use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use App\Models\Utils\ActionResult;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Validator;

class DestroyEmployeeAttachment
{
    use AsAction;
    use HasValidateModelAttachment;

    public function handle(Attachment $attachment): ActionResult
    {
        $res = new ActionResult();

        $attachment->delete();


        $res->model    = $attachment;
        $res->model_id = $attachment->id;
        $res->status   = 'deleted';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit');
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
            $this->handle($attachment,)
        );
    }

}
