<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 17:29:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\WorkTarget;


use App\Models\HumanResources\WorkTarget;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWorkTarget
{
    use AsAction;
    use WithUpdate;

    public function handle(WorkTarget $workTarget, array $workTargetData): ActionResult
    {
        $res = new ActionResult();


        $workTarget->update(
            Arr::except($workTargetData, ['data'])
        );
        $workTarget->update($this->extractJson($workTargetData, ['data']));

        $res->changes = array_merge($res->changes, $workTarget->getChanges());


        $res->model = $workTarget;

        $res->model_id = $workTarget->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }


}
