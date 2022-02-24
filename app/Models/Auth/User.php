<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 12 Sep 2021 04:33:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Auth;

use App\Models\Account\Tenant;
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
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Permission\Traits\HasRoles;


/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
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
        'status'   => 'boolean',
        'admin'   => 'boolean'
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
        return $this->tenant->appType->code;
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
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
        return match ($this->userable_type) {
            'Employee' => ['fal', 'clipboard-user'],
            'Guest' => ['fal', 'user-alien'],
            default => ['fal', 'male']
        };
    }

    public function getLocalisedUserableTypeAttribute(): string
    {
        return match ($this->userable_type) {
            'Employee' => __('Employee'),
            'Guest' => __('Guest'),
            'Tenant' => __('Account administrator'),
            default => $this->userable_type
        };
    }


}
