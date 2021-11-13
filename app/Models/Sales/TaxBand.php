<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 12 Nov 2021 02:32:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

/**
 * @mixin IdeHelperTaxBand
 */
class TaxBand extends Model
{
    use HasFactory;
    use UsesLandlordConnection;
    use SoftDeletes;

    protected $casts = [
        'data' => 'array',
        'status'   => 'boolean'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];
}
