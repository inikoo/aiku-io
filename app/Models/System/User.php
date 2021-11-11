<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 12 Sep 2021 04:33:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Traits\HasRoles;


/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use UsesTenantConnection;
    use HasRoles;

    protected $guarded = [];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'status'   => 'boolean'
    ];


    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    public function images(): MorphMany
    {
        return $this->morphMany('App\Models\Helpers\ImageModel', 'image_model', 'imageable_type', 'imageable_id');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(UserStats::class, 'id', 'id');
    }

}
