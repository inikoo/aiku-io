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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Permission\Traits\HasRoles;


/**
 * @mixin IdeHelperAdminUser
 */
class AdminUser extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    use UsesLandlordConnection;
    use HasRoles;
    use SoftDeletes;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'status'   => 'boolean'
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $hidden = [
        'password',
    ];

    protected $guarded = [];

    /**
     * @return string
     * Hack for laravel permissions to work
     */
    public function guardName(): string
    {
        return 'aiku';
    }

    public function accountAdmin(): BelongsTo
    {
        return $this->belongsTo(AccountAdmin::class);
    }


}
