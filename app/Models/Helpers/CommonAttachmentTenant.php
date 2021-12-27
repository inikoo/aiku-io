<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Dec 2021 13:04:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


namespace App\Models\Helpers;

use App\Providers\CommonAttachmentAnchoring;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperCommonAttachmentTenant
 */
class CommonAttachmentTenant extends Pivot
{
    protected $connection = 'media';


    protected $dispatchesEvents = [
        'created' => CommonAttachmentAnchoring::class,
        'deleted' => CommonAttachmentAnchoring::class,
    ];





}
