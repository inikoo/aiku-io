<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Feb 2022 18:00:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Marketing;

use App\Actions\Hydrators\HydrateDepartment;
use App\Actions\Hydrators\HydrateShop;
use App\Models\Media\Image;
use App\Models\SalesStats;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperFamily
 */
class Family extends Model  implements Auditable
{
    use HasSlug;
    use \OwenIt\Auditing\Auditable;
    use UsesTenantConnection;
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (Family $family) {
                if($family->department_id){
                    HydrateDepartment::make()->familiesStats($family->department);
                }
                HydrateShop::make()->familiesStats($family->shop);
            }
        );
        static::deleted(
            function (Family $family) {
                if($family->department_id){
                    HydrateDepartment::make()->familiesStats($family->department);
                }
                HydrateShop::make()->familiesStats($family->shop);
            }
        );
        static::updated(function (Family $family) {
            if ($family->wasChanged('state')) {
                if($family->department_id){
                    HydrateDepartment::make()->familiesStats($family->department);
                }
                HydrateShop::make()->familiesStats($family->shop);
            }
        });
    }


    public function getSlugSourceAttribute(): string
    {
        return $this->code.'-'.$this->shop->code;
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(FamilyStats::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image_model', 'imageable_type', 'imageable_id');
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
