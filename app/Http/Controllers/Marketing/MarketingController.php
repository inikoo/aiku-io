<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Feb 2022 01:16:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Marketing;

use App\Actions\Marketing\ShowMarketingDashboard;
use App\Http\Controllers\Controller;
use Inertia\Response;


class MarketingController extends Controller
{

    public function dashboard(): Response
    {
        return ShowMarketingDashboard::make()->asInertia();
    }



}
