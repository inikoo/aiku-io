<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:18:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Inventory;

use App\Actions\Hydrators\HydrateWarehouse;
use App\Actions\Hydrators\HydrateWarehouseArea;
use App\Models\LocationStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperLocation
 */
class Location extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    use Searchable;


    protected $casts = [
        'data' => 'array',
        'audited_at' => 'array'
    ];

    protected static function booted()
    {
        static::created(
            function (Location $location) {
                HydrateWarehouse::run($location->warehouse);
                HydrateWarehouseArea::run($location->warehouseArea);
            }
        );
        static::deleted(
            function (Location $location) {
                HydrateWarehouse::run($location->warehouse);
                HydrateWarehouseArea::run($location->warehouseArea);
            }
        );
    }

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo('App\Models\Inventory\Warehouse');
    }

    public function warehouseArea(): BelongsTo
    {
        return $this->belongsTo('App\Models\Inventory\WarehouseArea');
    }

    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class)->using(LocationStock::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(LocationStats::class);
    }

}
