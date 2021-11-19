<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 18 Nov 2021 16:16:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Sales;

use App\Models\Trade\Shop;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @mixin IdeHelperShippingSchema
 */
class ShippingSchema extends Model implements Auditable
{
    use HasFactory;
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $guarded = [];

    /** @noinspection PhpUnused */
    public function getSlugSourceAttribute(): string
    {

        $suffix='';
        if($this->type=='deal'){
            $suffix='_'.$this->type;
        }

        return $this->shop->code.$suffix;
    }


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public  function shippingZone(): HasMany
    {
        return $this->hasMany(ShippingZone::class);
    }


}
