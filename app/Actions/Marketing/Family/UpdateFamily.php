<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Feb 2022 19:20:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Family;

use App\Models\Marketing\Family;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateFamily
{
    use AsAction;
    use WithUpdate;

    public function handle(Family $family, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $family->update($modelData);

        $res->changes = array_merge($res->changes, $family->getChanges());

        $res->model    = $family;
        $res->model_id = $family->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
