<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Feb 2022 01:47:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;



/**
 * @mixin IdeHelperAdminUser
 */
class AdminUser extends Authenticatable
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

    public function accountAdmin(): BelongsTo
    {
        return $this->belongsTo(AccountAdmin::class);
    }


}
