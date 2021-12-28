<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 20:27:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Employee;

use App\Actions\Media\Image\Traits\HasValidateModelImage;
use App\Actions\WithUpdate;
use App\Http\Resources\Utils\ActionResultResource;
use App\Models\HumanResources\Employee;
use App\Models\Media\Image;
use App\Models\Utils\ActionResult;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateEmployeeImage
{
    use AsAction;
    use WithUpdate;

    use HasValidateModelImage;

    public function handle(Image $image, array $imageData): ActionResult
    {
        $res = new ActionResult();

        $image->update($imageData);
        $res->model    = $image;
        $res->changes  = $image->getChanges();
        $res->model_id = $image->id;
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
            'scope'   => [
                'sometimes',
                'required',
                Rule::in(['profile']),
            ],
        ];
    }


    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        /** @var Employee $employee */
        /** @var Image $image */

        $employee = $request->route('employee');

        $image = $request->route('image');

        if (!$this->validateModelImage(model: $employee, image: $image)) {
            $validator->errors()->add('image_mode', 'Image do not belongs to Model.');
        }
    }


    /** @noinspection PhpUnusedParameterInspection */
    public function asController(Employee $employee, Image $image, ActionRequest $request): ActionResultResource
    {
        return new ActionResultResource(
            $this->handle(

                $image,
                $request->only(
                    'caption',
                    'scope')
            )
        );
    }

}
