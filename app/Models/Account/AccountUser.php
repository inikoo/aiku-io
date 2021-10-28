<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 18 Sep 2021 01:20:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;


/**
 * @mixin IdeHelperAccountUser
 */
class AccountUser extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use UsesLandlordConnection;

    protected $guarded = [];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array'
    ];


    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

}
