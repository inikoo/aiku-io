<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 01:42:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperStockStats
 */
class StockStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'stock_stats';

    protected $guarded = [];


    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }


}
