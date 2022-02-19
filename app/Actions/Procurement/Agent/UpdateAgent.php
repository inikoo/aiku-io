<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:15:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Agent;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Procurement\Agent;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateAgent
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;


    private array $fields;

    public function __construct()
    {
        $this->fields=[
            'code',
            'name',
            'email',
            'phone',
            'status'
        ];
    }

    public function handle(Agent $agent, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $agent->update(Arr::except($modelData, ['data', 'settings']));
        $agent->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes  = $agent->getChanges();
        $res->model    = $agent;
        $res->model_id = $agent->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('procurement:edit') || $request->user()->hasPermissionTo("procurement.agents.edit");
    }

    public function rules(): array
    {
        return [
            'code'   => 'sometimes|required|alpha_dash',
            'name'   => 'sometimes|required|string',
            'email'  => 'sometimes|email',
            'phone'  => 'sometimes|phone:AUTO',
            'status' => 'sometimes|required|boolean'


        ];
    }

    public function asController(Agent $agent, ActionRequest $request): ActionResultResource
    {
        $request->validate();

        $modelData = $request->only($this->fields);


        return new ActionResultResource(
            $this->handle(
                $agent,
                $modelData
            )
        );
    }

    public function asInertia(Agent $agent, Request $request): RedirectResponse
    {
        $this->set('agent', $agent);
        $this->fillFromRequest($request);
        $this->validateAttributes();

        $modelData = $request->only($this->fields);


        $this->handle(
            $agent,
            $modelData

        );

        return Redirect::route('procurement.agents.edit', $agent->id);
    }

}
