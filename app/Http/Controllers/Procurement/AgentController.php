<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 02:14:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Procurement;

use App\Actions\Procurement\Agent\IndexAgent;
use App\Actions\Procurement\Agent\ShowAgent;
use App\Http\Controllers\Controller;
use App\Models\Procurement\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class AgentController extends Controller
{


    public function index(): Response
    {
        return IndexAgent::make()->asInertia();
    }

    public function show(Agent $agent): Response
    {
        return ShowAgent::make()->asInertia($agent);
    }

    public function edit(Agent $agent): Response
    {

        return ShowEditAgent::make()->asInertia(agent: $agent);
    }

    public function update(Agent $agent, Request $request): RedirectResponse
    {
        return UpdateAgent::make()->asInertia(agent: $agent,request:  $request);
    }

}
