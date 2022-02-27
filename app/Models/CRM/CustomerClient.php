<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 23:42:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\CRM;

use App\Models\Helpers\Address;
use App\Models\Marketing\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperCustomerClient
 */
class CustomerClient extends Model implements Auditable
{
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'location' => 'array',
        'deactivated_at'=>'datetime'
    ];

    protected $attributes = [
        'location' => '{}',
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

    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable')->withTimestamps();
    }

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }



}
