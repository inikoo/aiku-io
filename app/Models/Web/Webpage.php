<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 26 Mar 2022 00:56:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @mixin IdeHelperWebpage
 */
class Webpage extends Model implements Auditable
{
    use HasFactory;
    use UsesTenantConnection;
    use HasSlug;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $casts = [
        'locked'            => 'boolean',
    ];
    protected $guarded = [];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->extraScope(fn ($builder) => $builder->where('website_id', $this->website_id));
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebpageStats::class);
    }

    public function layouts(): HasMany
    {
        return $this->hasMany(WebpageLayout::class);
    }

}
