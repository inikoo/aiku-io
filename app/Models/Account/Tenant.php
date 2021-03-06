<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 18:49:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Account;

use App\Models\Assets\Country;
use App\Models\Assets\Language;
use App\Models\HumanResources\Workplace;
use App\Models\Inventory\Stock;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use App\Models\Production\Workshop;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;



/**
 * @mixin IdeHelperTenant
 */
class Tenant extends SpatieTenant
{

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
        static::updated(function ($tenant) {
            if ($tenant->wasChanged('language_id')) {
                $tenant->getAdminUser()->update([
                                          'language_id' => $tenant->language_id
                                      ]);
            }
        });
    }

    public function getDatabaseName(): string
    {
        return $this->code.'_aiku';
    }

    public function appType(): BelongsTo
    {
        return $this->belongsTo('App\Models\Aiku\AppType');
    }

    public function getAdminUser(): User
    {

        return (new User())->where('tenant_id', $this->id)->where('admin', true)->first();
    }



    public function adminUser(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function agents(): MorphMany
    {
        return $this->morphMany(Agent::class, 'owner');
    }

    public function suppliers(): MorphMany
    {
        return $this->morphMany(Supplier::class, 'owner');
    }

    public function workshops(): MorphMany
    {
        return $this->morphMany(Workshop::class, 'owner');
    }

    public function workplaces(): MorphMany
    {
        return $this->morphMany(Workplace::class, 'owner');
    }

    public function stocks(): MorphMany
    {
        return $this->morphMany(Stock::class, 'owner');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }


    public function stats(): HasOne
    {
        return $this->hasOne(TenantStats::class);
    }

    public function marketingStats(): HasOne
    {
        return $this->hasOne(TenantMarketingStats::class);
    }

    public function inventoryStats(): HasOne
    {
        return $this->hasOne(TenantInventoryStats::class);
    }

    public function procurementStats(): HasOne
    {
        return $this->hasOne(TenantProcurementStats::class);
    }

    public function productionStats(): HasOne
    {
        return $this->hasOne(TenantProductionStats::class);
    }

    public function staffingStats(): HasOne
    {
        return $this->hasOne(TenantStaffingStats::class);
    }

    public function salesStats(): HasMany
    {
        return $this->hasMany(TenantSalesStats::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tenantWebsites(): HasMany
    {
        return $this->hasMany(TenantWebsite::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
