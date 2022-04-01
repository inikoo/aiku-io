<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 22 Mar 2022 23:16:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Account;


use App\Actions\Setup\SetupIrisWebsite;
use App\Models\Auth\LandlordPersonalAccessToken;
use App\Models\Web\Website;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @mixin IdeHelperTenantWebsite
 */
class TenantWebsite extends Model
{

    use HasSlug;
    use UsesLandlordConnection;
    use SoftDeletes;
    use HasApiTokens;


    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (TenantWebsite $tenantWebsite) {
                Sanctum::usePersonalAccessTokenModel(LandlordPersonalAccessToken::class);
                $tenantWebsite->iris_api_key=$tenantWebsite->createToken('iris-admin',['root'])->plainTextToken;
                $tenantWebsite->save();

                SetupIrisWebsite::run($tenantWebsite);



            }
        );


    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }


}
