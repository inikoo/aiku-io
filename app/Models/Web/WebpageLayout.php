<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 16:55:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use App\Actions\Hydrators\HydrateWebpage;
use App\Actions\Hydrators\HydrateWebsite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperWebpageLayout
 */
class WebpageLayout extends Model
{

    use UsesTenantConnection;


    protected $guarded = [];

    protected static function booted()
    {
        static::created(
            function (WebpageLayout $webpageLayout) {
                HydrateWebpage::make()->layoutsStats($webpageLayout->webpage);
                HydrateWebsite::make()->webpagesStats($webpageLayout->website);

            }
        );
        static::deleted(
            function (WebpageLayout $webpageLayout) {
                HydrateWebpage::make()->layoutsStats($webpageLayout->webpage);
                HydrateWebsite::make()->webpagesStats($webpageLayout->website);
            }
        );

    }

    public function webpage(): BelongsTo
    {
        return $this->belongsTo(Webpage::class);
    }
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebpageLayoutStats::class);
    }

}
