<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:49:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\Website;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWebsite
{
    use AsAction;
    use WithUpdate;

    public function handle(
        Website $website,
        array $modelData
    ): ActionResult {
        $res = new ActionResult();

        $website->update(Arr::except($modelData, ['data', 'settings']));
        $website->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = $website->getChanges();

        $res->model    = $website;
        $res->model_id = $website->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
