<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 21 Dec 2021 16:37:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment\Traits;


use App\Http\Resources\Helpers\ModelAttachmentResource;
use App\Models\Helpers\Attachment;
use App\Models\HumanResources\Employee;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

trait HasModelAttachments
{

    public function jsonResponse(Employee $model): AnonymousResourceCollection
    {
        $attachments = QueryBuilder::for(Attachment::class)
            ->where('attachmentable_type', class_basename($model))
            ->where('attachmentable_id', $model->id)
            ->paginate();

        return ModelAttachmentResource::collection($attachments);
    }

}
