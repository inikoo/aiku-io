<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 21:13:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\CommonAttachment;

use App\Http\Resources\Helpers\CommonAttachmentResource;
use App\Models\Helpers\CommonAttachment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class ShowCommonAttachments
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }



    public function jsonResponse(): AnonymousResourceCollection
    {
        $attachments = QueryBuilder::for(CommonAttachment::class)
            ->allowedIncludes(['models'])
            ->paginate();

        return CommonAttachmentResource::collection($attachments);
    }
}
