<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 05 Jan 2022 14:54:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\ClockingMachine;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\HumanResources\ClockingMachine;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateClockingMachine
{
    use AsAction;
    use WithUpdate;

    public function handle(ClockingMachine $clockingMachine, array $clockingMachineData): ActionResult
    {
        $res = new ActionResult();


        $clockingMachine->update(
            Arr::except($clockingMachineData, [
                'data',


            ])
        );
        $clockingMachine->update($this->extractJson($clockingMachineData, ['data']));

        $res->changes = $clockingMachine->getChanges();
        $res->model   = $clockingMachine;

        $res->model_id = $clockingMachine->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('human-resources:edit');
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|required|string',
        ];
    }


    public function asController(ClockingMachine $clockingMachine, ActionRequest $request): ActionResultResource
    {
        return new ActionResultResource(
            $this->handle(
                $clockingMachine,

                $request->only(
                    'code',
                )
            )
        );
    }

}
