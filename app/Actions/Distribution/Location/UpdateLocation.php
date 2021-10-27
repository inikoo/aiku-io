<?php

namespace App\Actions\Distribution\Location;

use App\Actions\Migrations\MigrationResult;
use App\Models\Distribution\Location;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateLocation
{
    use AsAction;

    public function handle(Location $location, array $data): MigrationResult
    {
        $res = new MigrationResult();

        $location->update($data);
        $res->model    = $location;
        $res->model_id = $location->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
