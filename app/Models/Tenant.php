<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 15 Aug 2021 01:46:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models;

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
        'data'     => 'array'
    ];

}
