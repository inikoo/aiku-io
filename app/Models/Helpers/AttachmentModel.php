<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:15:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Helpers;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperAttachmentModel
 */
class AttachmentModel extends Pivot {
    use UsesTenantConnection;


    protected $guarded =[];

    public function attachment(): BelongsTo
    {
        return $this->belongsTo(Attachment::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'attachmentable_type', 'attachmentable_id');
    }

}

