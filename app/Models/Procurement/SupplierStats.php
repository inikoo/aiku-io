<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 02:48:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;



/**
 * @mixin IdeHelperSupplierStats
 */
class SupplierStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'supplier_stats';

    protected $guarded = [];


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }


}
