<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 00:32:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Media;

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


