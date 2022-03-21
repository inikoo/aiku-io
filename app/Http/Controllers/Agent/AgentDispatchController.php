<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 20 Mar 2022 01:44:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Agent;

use App\Actions\Agent\ShowAgentDispatchDashboard;
use App\Http\Controllers\Controller;
use Inertia\Response;


class AgentDispatchController extends Controller
{

    public function dashboard(): Response
    {
        return ShowAgentDispatchDashboard::make()->asInertia();
    }



}
