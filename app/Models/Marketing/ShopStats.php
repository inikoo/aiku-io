<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Feb 2022 20:26:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperShopStats
 */
class ShopStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'shop_stats';

    protected $guarded = [];


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }


}
