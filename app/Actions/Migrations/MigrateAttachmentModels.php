<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 15:23:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Models\Buying\PurchaseOrder;
use App\Models\Buying\Supplier;
use App\Models\CRM\Customer;
use App\Models\Helpers\AttachmentModel;
use App\Models\HumanResources\Employee;
use App\Models\Inventory\Stock;
use App\Models\Sales\Order;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateAttachmentModels
{
    use AsAction;

    public function handle(Employee|Supplier|Customer|Order|PurchaseOrder|Stock $model, $attachmentModelsData)
    {
        $old = [];
        $new = [];

        $model->attachments()->get()->each(
            function ($attachmentModel) use (&$old) {
                $old[] = $attachmentModel->id;
            }
        );

        foreach ($attachmentModelsData as $attachmentModelData) {
            if ($attachmentModelData->{'attachment_id'}) {
                $scope = Str::snake(strtolower($attachmentModelData->{'Attachment Subject Type'}), '-');


                AttachmentModel::upsert([
                                            [
                                                'attachmentable_type' => $model->getMorphClass(),
                                                'attachmentable_id'   => $model->id,
                                                'scope'               => $scope,
                                                'filename'            => Str::of($attachmentModelData->{'Attachment File Original Name'})->limit(255),
                                                'attachment_id'       => $attachmentModelData->{'attachment_id'},
                                                'data'                => '{}'
                                            ],
                                        ],
                                        ['attachment_id', 'attachmentable_id', 'attachmentable_type', 'scope'],
                                        ['filename']
                );


                $attachmentModel = AttachmentModel::where('attachmentable_type', $model->getMorphClass())
                    ->where('attachmentable_id', $model->id)
                    ->where('attachment_id', $attachmentModelData->{'attachment_id'})
                    ->where('scope', $scope)
                    ->first();


                $new[] = $attachmentModel->id;
                $model->attachments()->save($attachmentModel);
            }
        }


        $model->attachments()->whereIn('id', array_diff($old, $new))->delete();
    }
}
