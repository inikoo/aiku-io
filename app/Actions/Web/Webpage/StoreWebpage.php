<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:45:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\Webpage;


use App\Actions\Web\WebpageLayout\StoreWebpageLayout;
use App\Models\Utils\ActionResult;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreWebpage
{
    use AsAction;

    public function handle(Website $website, array $data): ActionResult
    {
        $res = new ActionResult();

        /** @var \App\Models\Web\Webpage $webpage */
        $webpage = $website->webpages()->create($data);
        $webpage->stats()->create();

        StoreWebpageLayout::run($webpage,[]);

        $res->model    = $webpage;
        $res->model_id = $webpage->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
