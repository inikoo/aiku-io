<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 26 Mar 2022 01:41:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Web\WebsiteComponent;

use App\Models\Utils\ActionResult;
use App\Models\Web\WebsiteComponentBlueprint;
use Lorisleiva\Actions\Concerns\AsAction;



class StoreWebsiteComponent
{
    use AsAction;

    public function handle(WebsiteComponentBlueprint $websiteComponentBlueprint,array $modelData): ActionResult
    {
        $res  = new ActionResult();

        $modelData['type']=$websiteComponentBlueprint->type;
        $modelData['name']=$websiteComponentBlueprint->name;
        $modelData['arguments']=$websiteComponentBlueprint->sample_arguments;

        /** @var \App\Models\Web\WebsiteComponent $websiteComponent */
        $websiteComponent = $websiteComponentBlueprint->websiteComponents()->create($modelData);

        $res->model    = $websiteComponent;
        $res->model_id = $websiteComponent->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }


}
