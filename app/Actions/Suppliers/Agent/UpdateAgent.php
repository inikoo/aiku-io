<?php

namespace App\Actions\Suppliers\Agent;

use App\Models\Suppliers\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAgent
{
    use AsAction;

    public function handle(Agent $agent,array $data): Agent
    {
        $agent->update($data);
        return $agent;
    }
}
