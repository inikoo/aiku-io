<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 21:08:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Image\Employee;

use App\Actions\Media\RawImage\StoreRawImage;
use App\Actions\WithUpdate;
use App\Http\Resources\Utils\ActionResultResource;
use App\Models\HumanResources\Employee;
use App\Models\Media\Image;
use App\Models\Utils\ActionResult;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

use function app;
use function response;

class StoreEmployeeImage
{
    use AsAction;
    use WithUpdate;


    public function handle(Employee $employee, array $imageData): ActionResult
    {
        $res = new ActionResult();


        $resRawImage = StoreRawImage::run($imageData['file']->path(),
                                          [
                                              'mime' => $imageData['file']->getMimeType(),
                                          ]
        );

        $imageData['communal_image_id'] = $resRawImage->model->communalImage->id;

        /** @var \App\Models\Media\Image $image */

        $imageData['filename'] = $imageData['file']->getClientOriginalName();




        try {
            $image = $employee->images()->create(
                Arr::except(
                    $imageData,
                    ['file']
                )
            );


            $resRawImage->model->communalImage->tenants()->attach(App('currentTenant')->id);
        } catch (Exception $e) {
            $res->status = 'error';



            if ($image = Image::onlyTrashed()
                ->where('imageable_type', $employee->getMorphClass())
                ->where('imageable_id', $employee->id)
                ->where('communal_image_id', $resRawImage->model->communalImage->id)
                ->where('scope', $imageData['scope'])
                ->first()
            ) {


                $image->restore();

                $image->update(
                    Arr::except(
                        $imageData,
                        ['file', 'imageable_type', 'imageable_id', 'communal_image_id', 'scope']
                    )
                );
            } else {
                if ($e->getCode() == 23505) {
                    $res->errors['image'] = 'Image already associated with model';
                } else {
                    $res->errors['image'] = 'Database error';
                    if(config('app.env') == 'local' ){
                        $res->errors['image'] .= ' '.$e->getCode().' '.$e->getMessage();

                    }
                }


                return $res;
            }
        }


        $res->model                  = $image;
        $res->model_id               = $image->id;
        $res->data['aiku_master_id'] = $image->communal_image_id;
        $res->status                 = $res->model_id ? 'inserted' : 'error';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit');
    }

    public function rules(): array
    {
        return [
            'file'      => 'required|file|max:100000',
            'caption'   => 'sometimes|nullable|string',
            'scope'     => [
                'required',
                Rule::in(['profile']),
            ],
            'aurora_id' => 'sometimes|required|integer',

        ];
    }


    public function asController(Employee $employee, ActionRequest $request): ActionResultResource|JsonResponse
    {
        $actionResult = $this->handle(

            $employee,
            $request->only(
                'caption',
                'scope',
                'file',
                'aurora_id'
            )
        );

        if ($actionResult->status == 'error') {
            return response()->json([
                                        'message' => 'Image can not be added',
                                        'errors'  => $actionResult->errors,
                                    ], 422);
        } else {
            return new ActionResultResource($actionResult);
        }
    }

}
