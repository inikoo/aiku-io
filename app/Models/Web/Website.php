<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:31:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use App\Models\Auth\WebsiteUser;
use App\Models\Marketing\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @mixin IdeHelperWebsite
 */
class Website extends Model implements Auditable
{
    use HasSlug;
    use UsesTenantConnection;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;


    protected $casts = [
        'data'     => 'array',
        'settings' => 'array'
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebsiteStats::class);
    }

    public function layout(): HasOne
    {
        return $this->hasOne(WebsiteLayout::class);
    }

    public function websiteUsers(): BelongsToMany
    {
        return $this->belongsToMany(WebsiteUser::class);
    }

    public function webpages(): HasMany
    {
        return $this->hasMany(Webpage::class);
    }

}
