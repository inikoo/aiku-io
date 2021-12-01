<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 30 Nov 2021 23:59:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Sales;

use App\Models\Trade\Shop;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperAdjust
 */
class Adjust extends Model
{
    use HasFactory;
    use HasSlug;
    use UsesTenantConnection;

    protected $guarded = [];


    /** @noinspection PhpUnused */
    public function getSlugSourceAttribute(): string
    {
        return $this->shop->code.'_'.$this->type;
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

}
