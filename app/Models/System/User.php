<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 12 Sep 2021 04:33:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\System;

use App\Actions\Hydrators\HydrateTenant;
use App\Models\Assets\Language;
use App\Models\Media\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    use SoftDeletes;

    protected $guarded = [];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
        'errors'   => '{}',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'errors'   => 'array',
        'status'   => 'boolean'
    ];

    protected static function booted()
    {
        static::created(
            function () {
                HydrateTenant::make()->userStats();
            }
        );
        static::deleted(
            function () {
                HydrateTenant::make()->userStats();
            }
        );

    }


    public function userable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(UserStats::class, 'id', 'id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function getTypeIconAttribute(): array
    {
        return match($this->userable_type){
            'Employee'=>['fal','clipboard-user'],
            'Guest'=>['fal','user-alien'],
            default=>['fal','male']
        };
    }

    public function getLocalisedUserableTypeAttribute(): string
    {
        return match($this->userable_type){
            'Employee'=>__('Employee'),
            'Guest'=>__('Guest'),
            'Tenant'=>__('Account administrator'),
            default=>$this->userable_type
        };
    }



}
