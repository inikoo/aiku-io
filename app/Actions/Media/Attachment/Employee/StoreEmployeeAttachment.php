<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 15:22:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Media\Attachment\Employee;

use App\Actions\Helpers\CommonAttachment\StoreCommonAttachment;
use App\Actions\WithUpdate;
use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use App\Models\Utils\ActionResult;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

use function app;
use function response;

class StoreEmployeeAttachment
{
    use AsAction;
    use WithUpdate;


    public function handle(Employee $employee, array $attachmentData): ActionResult
    {
        $res = new ActionResult();


        $resCommomAttachment = StoreCommonAttachment::run($attachmentData['file']->path(),
                                                          [
                                                              'mime'      => $attachmentData['file']->getMimeType(),
                                                              'extension' => $attachmentData['file']->extension()
                                                          ]
        );

        $attachmentData['common_attachment_id'] = $resCommomAttachment->model_id;

        /** @var \App\Models\Helpers\Attachment $attachment */

        $attachmentData['filename'] = $attachmentData['file']->getClientOriginalName();


        try {
            $attachment = $employee->attachments()->create(
                Arr::except(
                    $attachmentData,
                    ['file']
                )
            );

            $resCommomAttachment->model->tenants()->attach(App('currentTenant')->id);
        } catch (Exception $e) {
            $res->status = 'error';


            if ($attachment = Attachment::onlyTrashed()
                ->where('attachmentable_type', $employee->getMorphClass())
                ->where('attachmentable_id', $employee->id)
                ->where('common_attachment_id', $resCommomAttachment->model_id)
                ->where('scope', $attachmentData['scope'])->first()
            ) {
                $attachment->restore();

                $attachment->update(
                    Arr::except(
                        $attachmentData,
                        ['file','attachmentable_type','attachmentable_id','common_attachment_id','scope']
                    )
                );

            }else{

                if ($e->getCode() == 23505) {
                    $res->errors['attachment'] = 'Attachment already associated with model';
                } else {
                    $res->errors['attachment'] = 'Database error '.$e->getCode();
                }


                return $res;
            }


        }


        $res->model                  = $attachment;
        $res->model_id               = $attachment->id;
        $res->data['aiku_master_id'] = $attachment->common_attachment_id;
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
            'public'    => 'sometimes|required|boolean',
            'scope'     => [
                'required',
                Rule::in(['cv', 'contract', 'other']),
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
                'public',
                'file',
                'aurora_id'
            )
        );

        if ($actionResult->status == 'error') {
            return response()->json([
                                        'message' => 'Attachment can not be added',
                                        'errors'  => $actionResult->errors,
                                    ], 422);
        } else {
            return new ActionResultResource($actionResult);
        }
    }

}
