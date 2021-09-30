<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 15:27:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Models\Traits;

use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug as BaseHasSlug;

trait HasSlug{

    use BaseHasSlug;
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->slugSettings['source']??'slug_source')
            ->slugsShouldBeNoLongerThan($this->slugSettings['length']??32)
            ->saveSlugsTo($this->slugSettings['field']??'slug');
    }
}


