<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 16:33:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin IdeHelperWebpageLayoutStats
 */
class WebpageLayoutStats extends Model
{

    use UsesTenantConnection;

    protected $table = 'webpage_layout_stats';

    protected $guarded = [];


    public function webpageLayout(): BelongsTo
    {
        return $this->belongsTo(WebpageLayout::class);
    }


}
