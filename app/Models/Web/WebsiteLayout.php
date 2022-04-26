<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 03:08:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use App\Actions\Hydrators\HydrateWebsite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWebsiteLayout
 */
class WebsiteLayout extends Model
{

    use UsesTenantConnection;


    protected $guarded = [];



    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }


}
