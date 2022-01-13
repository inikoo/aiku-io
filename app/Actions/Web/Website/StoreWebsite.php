<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:45:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\Website;

use App\Models\Utils\ActionResult;
use App\Models\Trade\Shop;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreWebsite
{
    use AsAction;

    public function handle(Shop $shop,array $data): ActionResult
    {
        $res  = new ActionResult();

        /** @var \App\Models\Web\Website $website */
        $website = $shop->website()->create($data);

        $res->model    = $website;
        $res->model_id = $website->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
