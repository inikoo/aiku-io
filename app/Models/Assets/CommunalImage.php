<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 12:55:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;


/**
 * @mixin IdeHelperCommunalImage
 */
class CommunalImage extends Pivot {
    use UsesLandLordConnection;



    protected $guarded =[];

    public function imageable(): MorphTo {
        return $this->morphTo();
    }

}
