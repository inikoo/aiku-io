<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 04:35:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperWebsiteStats
 */
class WebsiteStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'website_stats';

    protected $guarded = [];


    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }


}
