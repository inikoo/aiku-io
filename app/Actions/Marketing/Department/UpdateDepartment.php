<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Feb 2022 19:20:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Department;

use App\Models\Marketing\Department;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateDepartment
{
    use AsAction;
    use WithUpdate;

    public function handle(Department $department, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $department->update($modelData);

        $res->changes = array_merge($res->changes, $department->getChanges());

        $res->model    = $department;
        $res->model_id = $department->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
