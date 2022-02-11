<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 19:52:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperCustomerStats
 */
class CustomerStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'customer_stats';

    protected $casts = [
        'last_submitted_order_at' => 'datetime',
        'last_dispatched_delivery_at' => 'datetime',
        'last_invoiced_at' => 'datetime',
    ];
    protected $guarded = [];


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


}
