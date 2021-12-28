<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 00:34:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Media;

use App\Events\CommunalImageAnchoring;
use App\Models\Account\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 * @mixin IdeHelperCommunalImageTenant
 */
class CommunalImageTenant extends Pivot
{

    protected $connection = 'media';


    protected $dispatchesEvents = [
        'created' => CommunalImageAnchoring::class,
        'deleted' => CommunalImageAnchoring::class,
    ];


    public function communalImage(): BelongsTo
    {
        return $this->belongsTo(CommunalImage::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }


}
