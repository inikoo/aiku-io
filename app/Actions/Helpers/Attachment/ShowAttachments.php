<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 21:13:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Attachment;

use App\Http\Resources\Helpers\AttachmentResource;
use App\Models\Helpers\Attachment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class ShowAttachments
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
        $attachments = QueryBuilder::for(Attachment::class)
            ->allowedIncludes(['models'])
            ->paginate();

        return AttachmentResource::collection($attachments);
    }
}
