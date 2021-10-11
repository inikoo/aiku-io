<?php

namespace App\Actions\Suppliers\Agent;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Suppliers\Agent;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreAgent
{
    use AsAction;

    public function handle(array $data, $addressData): Agent
    {
        $agent                   = Agent::create($data);
        $addresses               = [];
        $address                 = StoreAddress::run($addressData);
        $addresses[$address->id] = ['scope' => 'contact'];
        $agent->addresses()->sync($addresses);
        $agent->address_id = $address->id;
        $agent->save();

        return $agent;
    }
}
