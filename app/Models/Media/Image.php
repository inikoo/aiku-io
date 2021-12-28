<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 00:31:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Media;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;



/**
 * @mixin IdeHelperImage
 */
class Image extends Model {
    use UsesTenantConnection;


    protected $casts = [
        'compression' => 'array'
    ];

    protected $attributes = [
        'compression' => '{}',
    ];

    protected $guarded = [];



    public function model(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'imageable_type', 'imageable_id');
    }

    public function communalImage(): BelongsTo
    {
        return $this->belongsTo(CommunalImage::class);

    }


}
