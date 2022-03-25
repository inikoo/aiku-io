<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 16:59:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;


use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Illuminate\Support\Collection;


class HydrateWebpage extends HydrateModel
{

    public string $commandSignature = 'hydrate:webpage {id} {--t|tenant=* : Tenant code}';


    public function handle(Webpage $webpage): void
    {
        $this->layoutsStats($webpage);
    }


    public function layoutsStats(Webpage $webpage)
    {
        $stats          = [
            'number_layouts' => $webpage->layouts->count(),
        ];
        $webpage->stats->update($stats);

    }




    protected function getModel(int $id): Website
    {
        return Website::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Website::withTrashed()->get();
    }







}


