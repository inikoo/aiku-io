<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 17:29:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Clocking;


use App\Models\HumanResources\Clocking;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateClocking
{
    use AsAction;
    use WithUpdate;

    public function handle(Clocking $clocking, array $clockingData): ActionResult
    {
        $res = new ActionResult();


        $clocking->update(
            Arr::only($clockingData,
                      [
                          'clocked_at',
                          'created_by_type',
                          'created_by_id',
                          'deleted_by_type',
                          'deleted_by_id',
                          'created_at',
                          'aurora_id'
                      ]
            )
        );
        $clocking->update($this->extractJson($clockingData, ['data']));

        $res->changes = array_merge($res->changes, $clocking->getChanges());


        $res->model = $clocking;

        $res->model_id = $clocking->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }


}
