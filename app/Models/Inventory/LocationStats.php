<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 20:02:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperLocationStats
 */
class LocationStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'location_stats';

    protected $guarded = [];


    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }


}
