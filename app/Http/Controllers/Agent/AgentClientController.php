<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 20 Mar 2022 10:51:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Agent;

use App\Actions\Agent\ShowAgentDispatchDashboard;
use App\Actions\CRM\Customer\IndexCustomerInTenant;
use App\Actions\CRM\Customer\ShowCustomerInShop;
use App\Http\Controllers\Controller;
use Inertia\Response;


class AgentClientController extends Controller
{

    public function index(): Response
    {
        return IndexCustomerAsAgentClient::make()->asInertia();
    }



}
