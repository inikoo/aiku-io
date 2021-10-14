<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 19:22:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\CRM;

use App\Models\Helpers\Contact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperCustomer
 */
class Customer extends Model implements Auditable
{
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

   // public function shop(): BelongsTo
   // {
   //     return $this->belongsTo('App\Models\Selling\Shop');
   // }

    public function addresses(): MorphToMany
    {
        return $this->morphToMany('App\Models\Helpers\Address', 'addressable')->withTimestamps();
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo('App\Models\Helpers\Address');
    }

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo('App\Models\Helpers\Address');
    }

    public function images(): MorphMany
    {
        return $this->morphMany('App\Models\Helpers\ImageModel', 'image_models', 'imageable_type', 'imageable_id');
    }

    public function contact(): MorphOne
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function vendor(): MorphTo
    {
        return $this->morphTo();
    }

    public function customers(): MorphMany
    {
        return $this->morphMany(Customer::class, 'vendor');
    }

}
