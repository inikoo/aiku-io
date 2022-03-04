<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 02:48:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Inventory;

use App\Actions\Hydrators\HydrateFulfilmentCustomer;
use App\Models\CRM\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperUniqueStock
 */
class UniqueStock extends Model implements Auditable
{
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'delivered_at' => 'datetime',


    ];

    protected static function booted()
    {
        static::created(
            function (UniqueStock $uniqueStock) {
                HydrateFulfilmentCustomer::run(customer: $uniqueStock->customer);
            }
        );
        static::deleted(
            function (UniqueStock $uniqueStock) {
                HydrateFulfilmentCustomer::run(customer: $uniqueStock->customer);
            }
        );
        static::updated(function (UniqueStock $uniqueStock) {
            if ($uniqueStock->wasChanged(['state','type'])) {
                HydrateFulfilmentCustomer::run(customer: $uniqueStock->customer);
            }
        });
    }


    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'stockable');
    }

    public function getFormattedId(): string
    {
        return sprintf('%05d',$this->id);
    }

}
