<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 12 Nov 2021 01:28:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Sales;

use App\Models\CRM\Customer;
use App\Models\Trade\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @property mixed $alt_delivery_address_id
 * @property mixed $deliveryAddress
 * @mixin IdeHelperBasket
 */
class Basket extends Model
{
    use HasFactory;

    use UsesTenantConnection;

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany('App\Models\Sales\BasketTransaction');
    }



    public function deliveryAddress(): MorphToMany
    {
        return $this->morphToMany('App\Models\Helpers\Address', 'addressable')->withTimestamps();
    }


}
