<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 12 Sep 2021 23:46:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\HumanResources;

use App\Actions\HumanResources\ShowHumanResourcesDashboard;
use App\Http\Controllers\Controller;
use Inertia\Response;


class HumanResourcesController extends Controller
{
    public function dashboard(): Response
    {
        return ShowHumanResourcesDashboard::make()->asInertia();
    }

}
