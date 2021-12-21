<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 20 Dec 2021 23:35:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\Traits;


use Illuminate\Database\Eloquent\Relations\Relation;

trait WhenMorphToLoaded
{
    public function whenMorphToLoaded($name, $map)
    {
        return $this->whenLoaded($name, function () use ($name, $map) {
            $morphType = $name . '_type';
            $morphAlias = $this->resource->$morphType;
            $morphClass = Relation::getMorphedModel($morphAlias);
            $morphResourceClass = $map[$morphClass];
            return new $morphResourceClass($this->resource->$name);
        });
    }
}
