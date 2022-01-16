<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 23:21:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Production;


use App\Http\Controllers\Controller;
use App\Models\Production\Workshop;
use Inertia\Response;


class WorkshopController extends Controller
{


    public function index(): Response
    {
        return IndexWorkShop::make()->asInertia();
    }

    public function show(Workshop $workshop): Response
    {
        return ShowWorkShop::make()->asInertia($workshop);
    }


}
