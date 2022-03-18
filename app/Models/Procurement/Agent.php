<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 08 Oct 2021 17:52:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Procurement;

use App\Actions\Hydrators\HydrateTenant;
use App\Models\Media\Image;
use App\Models\Auth\User;
use App\Models\Traits\HasAddress;
use App\Models\Traits\HasProcurement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperAgent
 */
class Agent extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use Searchable;
    use HasAddress;
    use HasProcurement;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'location' => 'array',
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
        'location' => '{}',
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function () {
                HydrateTenant::make()->agentsStats();

            }
        );
        static::deleted(
            function () {
                HydrateTenant::make()->agentsStats();
            }
        );
    }


    public function addresses(): MorphToMany
    {
        return $this->morphToMany('App\Models\Helpers\Address', 'addressable')->withTimestamps();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
    }

    public function suppliers(): MorphMany
    {
        return $this->morphMany(Supplier::class, 'owner');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(AgentStats::class);
    }


}
