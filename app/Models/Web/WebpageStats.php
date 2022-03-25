<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 16:28:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;


/**
 * @mixin IdeHelperWebpageStats
 */
class WebpageStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'webpage_stats';

    protected $guarded = [];


    public function webpage(): BelongsTo
    {
        return $this->belongsTo(Webpage::class);
    }


}
