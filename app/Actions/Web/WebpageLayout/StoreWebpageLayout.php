<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 16:56:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\WebpageLayout;


use App\Models\Utils\ActionResult;
use App\Models\Web\Webpage;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreWebpageLayout
{
    use AsAction;

    public function handle(Webpage $webpage, array $data): ActionResult
    {
        $res = new ActionResult();

        $data['website_id']=$webpage->website_id;

        /** @var \App\Models\Web\WebpageLayout $webpageLayout */
        $webpageLayout = $webpage->layouts()->create($data);
        $webpageLayout->stats()->create();


        $res->model    = $webpageLayout;
        $res->model_id = $webpageLayout->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
