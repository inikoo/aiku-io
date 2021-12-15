<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 05 Dec 2021 02:08:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\Shipper;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Delivery\Shipper;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateShipper
{
    use AsAction;
    use WithUpdate;

    public function handle(
        Shipper $shipper,
        array $contactData,
        array $modelData
    ): ActionResult {
        $res = new ActionResult();

        $shipper->contact()->update($contactData);
        $res->changes = array_merge($res->changes, $shipper->contact->getChanges());

        $shipper->update(Arr::except($modelData, ['data']));
        $shipper->update($this->extractJson($modelData, ['data']));

        $res->changes = array_merge($res->changes, $shipper->getChanges());

        $res->model    = $shipper;
        $res->model_id = $shipper->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
