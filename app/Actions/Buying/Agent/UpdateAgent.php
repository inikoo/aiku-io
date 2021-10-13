<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:15:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Buying\Agent;

use App\Models\Buying\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAgent
{
    use AsAction;

    public function handle(Agent $agent,array $data, array $contactData): Agent
    {
        $agent->contact()->update($contactData);
        $agent->update($data);
        return $agent;
    }
}
