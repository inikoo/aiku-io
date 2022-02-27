<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 27 Feb 2022 19:38:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Production\Workshop;

use App\Models\Production\Workshop;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWorkshop
{
    use AsAction;
    use WithUpdate;

    public function handle(Workshop $workshop, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $workshop->update( Arr::except($modelData, ['data','settings']));
        $workshop->update($this->extractJson($modelData,['data','settings']));

        $res->changes = $workshop->getChanges();


        $res->model    = $workshop;
        $res->model_id = $workshop->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';



        return $res;
    }
}
