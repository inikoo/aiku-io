<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 07 Nov 2021 17:19:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models;

use App\Actions\Hydrators\HydrateLocation;
use App\Actions\Hydrators\HydrateStock;
use App\Models\Inventory\Location;
use App\Models\Inventory\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperLocationStock
 */
class LocationStock extends Pivot
{
    use HasFactory;


    protected $casts = [
        'data'     => 'array',
        'settings' => 'array'
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (LocationStock $locationStock) {
                HydrateLocation::make()->stocks($locationStock->location);
                HydrateStock::make()->stocks($locationStock->stock);
            }
        );
        static::deleted(
            function (LocationStock $locationStock) {
                HydrateLocation::make()->stocks($locationStock->location);
                HydrateStock::make()->stocks($locationStock->stock);
            }
        );
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }


}
