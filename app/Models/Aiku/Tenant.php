<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 18:49:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Aiku;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;



/**
 * @mixin IdeHelperTenant
 */
class Tenant extends SpatieTenant
{
    protected $guarded = [];
    protected $attributes = [
        'data' => '{}',
    ];
    protected $casts = [
        'data' => 'array'
    ];

    public function business_type(): BelongsTo
    {
        return $this->belongsTo('App\Models\Aiku\BusinessType');
    }

}
