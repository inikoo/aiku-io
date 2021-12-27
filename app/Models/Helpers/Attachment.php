<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 13:15:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Helpers;


use App\Providers\AttachmentDeleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;



/**
 * @mixin IdeHelperAttachment
 */
class Attachment extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;


    protected $guarded = [];

    protected $dispatchesEvents = [
        'deleted' => AttachmentDeleted::class,
        'restored' => AttachmentDeleted::class,
    ];

    public function commonAttachment(): BelongsTo
    {
        return $this->belongsTo(CommonAttachment::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'attachmentable_type', 'attachmentable_id');
    }

}

