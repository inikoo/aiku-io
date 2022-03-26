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
use App\Models\CustomisedProduct;
use App\Models\Helpers\Address;
use App\Models\Helpers\Attachment;
use App\Models\Inventory\Stock;
use App\Models\Inventory\UniqueStock;
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
        'data'            => 'array',
        'tax_number_data' => 'array',
        'location'        => 'array',

    ];

    protected $attributes = [
        'data'            => '{}',
        'location'        => '{}',
        'tax_number_data' => '{}',

    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (Customer $customer) {
                if ($customer->shop->type == 'fulfilment_house') {
                    $customer->customerFulfilmentStats()->create(
                        [
                            'aurora_id' => $customer->aurora_id
                        ]
                    );
                }
                HydrateShop::make()->customerStats($customer->shop);
            }
        );
        static::deleted(
            function (Customer $customer) {
                if ($customer->shop->type == 'fulfilment_house') {
                    $customer->customerFulfilmentStats()->delete();
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


    public function favourites(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favourites')->using(Favourites::class)->withPivot('id', 'aurora_id')->withTimestamps();
    }

    public function reminders(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'back_to_stock_reminders')->using(Reminders::class)->withPivot('id', 'aurora_id', 'status', 'state', 'send_at', 'deleted_at')->withTimestamps();
    }

    public function portfolio(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'portfolio')->using(Portfolio::class)->withPivot('id', 'aurora_id', 'status', 'removed_at', 'customer_reference')->withTimestamps();
    }

    public function customisedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->using(CustomisedProduct::class)->withPivot('id', 'aurora_id')->withTimestamps();
    }


    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customerFulfilmentStats(): HasOne
    {
        return $this->hasOne(CustomerFulfilmentStats::class);
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
        return sprintf('%05d', $this->id);
    }

    public function stocks(): MorphMany
    {
        return $this->morphMany(Stock::class, 'owner');
    }

    public function uniqueStocks(): HasMany
    {
        return $this->hasMany(UniqueStock::class);
    }


}
