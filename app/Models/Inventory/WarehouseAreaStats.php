<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 19:44:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;



/**
 * @mixin IdeHelperWarehouseAreaStats
 */
class WarehouseAreaStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'warehouse_area_stats';

    protected $guarded = [];


    public function warehouseArea(): BelongsTo
    {
        return $this->belongsTo(WarehouseArea::class);
    }


}
