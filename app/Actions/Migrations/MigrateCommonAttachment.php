<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:56:24 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Helpers\CommonAttachment\StoreCommonAttachment;
use App\Models\Helpers\CommonAttachment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;
use Mimey\MimeTypes;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class MigrateCommonAttachment
{
    use AsAction;

    /**
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    public function handle($auroraAttachmentData): ?CommonAttachment
    {
        if ($attachmentData = $this->getAttachmentData($auroraAttachmentData)) {



            $res = StoreCommonAttachment::run(
                Arr::get($attachmentData, 'attachment_path'),
                Arr::only($attachmentData, ['mime', 'extension'])
            );

            /** @var \App\Models\Helpers\CommonAttachment $commonAttachment */
            $commonAttachment = $res->model;

            DB::connection('aurora')->table('Attachment Dimension')
                ->where('Attachment Key', $auroraAttachmentData->{'Attachment Key'})
                ->update(['aiku_master_id' => $commonAttachment->id]);


            $attachmentData['temporary_directory']->delete();

            return $commonAttachment;
        } else {
            return null;
        }
    }

    /**
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    protected function getAttachmentData($auroraAttachmentData): array
    {
        $temporaryDirectory = (new TemporaryDirectory(storage_path('app/tmp/migrations')))->create();

        $filename = $auroraAttachmentData->{'Attachment File Original Name'};
        $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
        $filename = mb_ereg_replace("([\.]{2,})", '', $filename);
        $filename = $filename ?? 'attachment.unk';
        if (!preg_match('/\./', $filename)) {
            $filename = $filename.'.unk';
        }
        $tmpPath = str_replace(storage_path('app'), '', $temporaryDirectory->path($filename));
        Storage::put($tmpPath, $auroraAttachmentData->{'Attachment Data'});

        $mimes = new MimeTypes();

        return [
            'temporary_directory' => $temporaryDirectory,
            'attachment_path'     => $temporaryDirectory->path($filename),
            'filename'            => $auroraAttachmentData->{'Attachment File Original Name'},
            'mime'                => $auroraAttachmentData->{'Attachment MIME Type'},
            'extension'           => $mimes->getExtension($auroraAttachmentData->{'Attachment MIME Type'})
        ];
    }
}
