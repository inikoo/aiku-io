<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 17:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Trade\Shop;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Trade\Shop;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateShop
{
    use AsAction;
    use WithUpdate;

    public function handle(
        Shop $shop,
        array $contactData,
        array $modelData
    ): ActionResult {
        $res = new ActionResult();

        $shop->contact->update($contactData);
        $res->changes = array_merge($res->changes, $shop->contact->getChanges());

        $shop->update(Arr::except($modelData, ['data', 'settings']));
        $shop->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = array_merge($res->changes, $shop->getChanges());

        $res->model    = $shop;
        $res->model_id = $shop->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
