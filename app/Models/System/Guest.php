<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 02:50:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\System;

use App\Actions\Hydrators\HydrateTenant;
use App\Models\HumanResources\Clocking;
use App\Models\HumanResources\Workplace;
use App\Models\Traits\HasPersonalData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperGuest
 */
class Guest extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Searchable;
    use HasPersonalData;

    protected $casts = [
        'data'          => 'array',
        'date_of_birth' => 'datetime:Y-m-d',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

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
        static::updated(function (Guest $guest) {
            if ($guest->wasChanged('name')) {
                $guest->user?->update(['name' => $guest->name]);
            }elseif ($guest->wasChanged('status')) {
                HydrateTenant::make()->userStats();
            }
        });
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function ownOffice(): morphOne
    {
        return $this->morphOne(Workplace::class, 'owner');
    }

    public function clockings(): MorphMany
    {
        return $this->morphMany(Clocking::class, 'clockable');
    }

}
