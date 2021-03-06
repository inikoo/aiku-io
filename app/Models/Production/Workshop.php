<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 14:36:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Production;

use App\Actions\Hydrators\HydrateTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWorkshop
 */
class Workshop extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected static function booted()
    {
        static::created(
            function () {
                HydrateTenant::make()->productionStats();
            }
        );
        static::deleted(
            function () {
                HydrateTenant::make()->productionStats();
            }
        );
    }

    protected $guarded = [];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }


    public function workshopProducts(): HasMany
    {
        return $this->hasMany(WorkshopProduct::class);
    }

}
