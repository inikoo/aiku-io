<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 20:18:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;



/**
 * @mixin IdeHelperWarehouseStats
 */
class WarehouseStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'warehouse_stats';

    protected $guarded = [];


    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }


}
