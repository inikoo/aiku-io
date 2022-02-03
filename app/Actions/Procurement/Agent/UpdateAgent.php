<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:15:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Agent;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Procurement\Agent;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAgent
{
    use AsAction;
    use WithUpdate;

    public function handle(Agent $agent, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $agent->update(Arr::except($modelData, ['data', 'settings']));
        $agent->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes  = $agent->getChanges();
        $res->model    = $agent;
        $res->model_id = $agent->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
