<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:15:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Buying\Agent;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Buying\Agent;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAgent
{
    use AsAction;
    use WithUpdate;

    public function handle(Agent $agent,array $modelData, array $contactData): MigrationResult
    {
        $res = new MigrationResult();

        $agent->contact()->update($contactData);

        $res->changes = array_merge($res->changes, $agent->contact->getChanges());


        $agent->update( Arr::except($modelData, ['data','settings']));
        $agent->update($this->extractJson($modelData,['data','settings']));

        $res->changes = array_merge($res->changes, $agent->getChanges());
        $res->model    = $agent;
        $res->model_id = $agent->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
