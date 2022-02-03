<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 04 Dec 2021 22:08:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Delivery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperShipper
 */
class Shipper extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $casts = [
        'data'   => 'array',
        'status' => 'boolean',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];



}
