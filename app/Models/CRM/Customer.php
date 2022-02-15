<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 19:22:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\CRM;

use App\Actions\Hydrators\HydrateShop;
use App\Models\Financials\Invoice;
use App\Models\CustomerProduct;
use App\Models\Helpers\Address;
use App\Models\Helpers\Attachment;
use App\Models\Media\Image;
use App\Models\Marketing\Product;
use App\Models\Marketing\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
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
    use Searchable;

    protected $casts = [
        'data' => 'array',
        'tax_number_data'=>'array',
        'location' => 'array',

    ];

    protected $attributes = [
        'data' => '{}',
        'location' => '{}',
        'tax_number_data' => '{}',

    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (Customer $customer) {
                if($customer->shop->type=='fulfilment_house'){
                    $customer->fulfilmentCustomer()->create(
                        [
                            'aurora_id'=>$customer->aurora_id
                        ]
                    );
                }
                HydrateShop::make()->customerStats($customer->shop);
            }
        );
        static::deleted(
            function (Customer $customer) {
                if($customer->shop->type=='fulfilment_house'){
                    $customer->fulfilmentCustomer()->delete();
                }
                HydrateShop::make()->customerStats($customer->shop);
            }
        );
        static::updated(function (Customer $customer) {
            if ($customer->wasChanged('trade_state')) {
                HydrateShop::make()->customerNumberInvoicesStats($customer->shop);
            }
        });
    }


    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable')->withTimestamps();
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachment_model', 'attachmentable_type', 'attachmentable_id');
    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->using(CustomerProduct::class)->withPivot('id','status', 'type','aurora_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function fulfilmentCustomer(): HasOne
    {
        return $this->hasOne(FulfilmentCustomer::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(CustomerClient::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(CustomerStats::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFormattedID(): string
    {
        return sprintf('%04d',$this->id);
    }

}
