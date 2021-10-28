<?php

namespace App\Actions\Distribution\Location;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Distribution\Location;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateLocation
{
    use AsAction;
    use WithUpdate;

    public function handle(Location $location, array $modelData): MigrationResult
    {
        $res = new MigrationResult();

        $location->update( Arr::except($modelData, ['data']));
        $location->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $location->getChanges());

        $res->model    = $location;
        $res->model_id = $location->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
