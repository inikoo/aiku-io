<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 12:47:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;



/**
 * @mixin IdeHelperRawImage
 */
class RawImage extends Model {

    protected $connection= 'media';

    protected $guarded =[];

    public function communalImage(): MorphOne {
        return $this->morphOne(CommunalImage::class, 'imageable');
    }



}


