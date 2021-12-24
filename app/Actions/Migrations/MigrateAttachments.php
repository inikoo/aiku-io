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
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use App\Models\Inventory\Stock;
use App\Models\Sales\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateAttachments
{
    use AsAction;

    public function handle(Employee|Supplier|Customer|Order|PurchaseOrder|Stock $model, $attachmentsData)
    {
        $old = [];
        $new = [];

        $model->attachments()->get()->each(
            function ($attachment) use (&$old) {
                $old[] = $attachment->id;
            }
        );

        foreach ($attachmentsData as $attachmentData) {
            if ($attachmentData->{'common_attachment_id'}) {
                $scope = Str::snake(strtolower($attachmentData->{'Attachment Subject Type'}), '-');


                Attachment::upsert([
                                            [
                                                'attachmentable_type' => $model->getMorphClass(),
                                                'attachmentable_id'   => $model->id,
                                                'scope'               => $scope,
                                                'filename'            => Str::of($attachmentData->{'Attachment File Original Name'})->limit(255),
                                                'common_attachment_id'       => $attachmentData->{'common_attachment_id'},
                                                'caption'             => $attachmentData->{'Attachment Caption'},
                                                'public'              => $attachmentData->{'Attachment Public'} === 'Yes',
                                                'aurora_id'           => $attachmentData->{'Attachment Bridge Key'}

                                            ],
                                        ],
                                   ['common_attachment_id', 'attachmentable_id', 'attachmentable_type', 'scope'],
                                   ['filename', 'aurora_id']
                );






                $attachment = Attachment::where('attachmentable_type', $model->getMorphClass())
                    ->where('attachmentable_id', $model->id)
                    ->where('common_attachment_id',$attachmentData->{'common_attachment_id'})
                    ->where('scope', $scope)
                    ->first();

                if($attachment){
                    DB::connection('aurora')->table('Attachment Bridge')
                        ->where('Attachment Bridge Key', $attachmentData->{'Attachment Bridge Key'})
                        ->update(['aiku_id' => $attachment->id]);


                    $new[] = $attachment->id;
                    $model->attachments()->save($attachment);


                }else{
                    print "Error migrating a attachment model";
                }


            }
        }


        $model->attachments()->whereIn('id', array_diff($old, $new))->delete();
    }
}
