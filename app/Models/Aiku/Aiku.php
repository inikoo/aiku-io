<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 12:58:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Aiku;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;


/**
 * @mixin IdeHelperAiku
 */
class Aiku extends Model
{
    use UsesLandlordConnection;

    protected $table='aiku';

    protected $guarded = [];
    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];
    protected $casts = [
        'data'     => 'array',
        'settings' => 'array'
    ];



}
