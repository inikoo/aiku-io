<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:17:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Trade;

use App\Actions\Hydrators\HydrateTenant;
use App\Models\Financials\Invoice;
use App\Models\CRM\Customer;
use App\Models\Sales\Adjust;
use App\Models\Sales\Charge;
use App\Models\Sales\Order;
use App\Models\Sales\ShippingSchema;
use App\Models\SalesStats;
use App\Models\Traits\HasAddress;
use App\Models\Web\Website;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperShop
 */
class Shop extends Model implements Auditable
{
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    use Searchable;
    use HasAddress;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'location' => 'array'
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
        'location' => '{}',
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function () {
                HydrateTenant::make()->shopStats();
            }
        );
        static::deleted(
            function () {
                HydrateTenant::make()->shopStats();
            }
        );

    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('code')
            ->doNotGenerateSlugsOnCreate()
            ->doNotGenerateSlugsOnUpdate();
    }



    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function shippingSchema(): HasMany
    {
        return $this->hasMany(ShippingSchema::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function adjusts(): HasMany
    {
        return $this->hasMany(Adjust::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function website(): HasOne
    {
        return $this->hasOne(Website::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(ShopStats::class);
    }

    public function salesStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope','sales');
    }
    public function salesTenantCurrencyStats(): MorphOne
    {
        return $this->morphOne(SalesStats::class, 'model')->where('scope','sales-tenant-currency');
    }


}
