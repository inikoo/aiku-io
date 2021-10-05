<?php

namespace App\Actions\Distribution\Location;

use App\Models\Distribution\Location;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateLocation
{
    use AsAction;

    public function handle(Location $location, array $data): Location
    {
        $location->update($data);
        return $location;
    }
}
