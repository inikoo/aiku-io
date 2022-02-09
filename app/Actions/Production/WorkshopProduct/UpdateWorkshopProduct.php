<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 04:51:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Production\WorkshopProduct;

use App\Models\Production\WorkshopProduct;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWorkshopProduct
{
    use AsAction;
    use WithUpdate;

    public function handle(WorkshopProduct $workshopProduct, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $workshopProduct->update(Arr::except($modelData, ['data', 'settings']));
        $workshopProduct->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = array_merge($res->changes, $workshopProduct->getChanges());

        $res->model    = $workshopProduct;
        $res->model_id = $workshopProduct->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
