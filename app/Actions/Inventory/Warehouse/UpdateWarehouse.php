<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:48:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\Warehouse;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Inventory\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

/**
 * @property Warehouse $warehouse
 */
class UpdateWarehouse
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;



    public function handle(Warehouse $warehouse, array $modelData): ActionResult
    {
        $res = new ActionResult();


        $warehouse->update(Arr::except($modelData, ['data', 'settings']));
        $warehouse->update($this->extractJson($modelData, ['data', 'settings']));

        $res->changes = array_merge($res->changes, $warehouse->getChanges());

        $res->model    = $warehouse;
        $res->model_id = $warehouse->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root')
            || $request->user()->tokenCan('inventory:edit')
            || $request->user()->hasPermissionTo("warehouses.edit.{$this->warehouse->id}");
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|required|alpha_dash|unique:App\Models\Inventory\Warehouse,code',
            'name' => 'sometimes|required|unique:App\Models\Inventory\Warehouse,name',

        ];
    }

    public function asController(Warehouse $warehouse, ActionRequest $request): ActionResultResource
    {
        $request->validate();


        return new ActionResultResource(
            $this->handle(
                $warehouse,
                $request->only(
                    'code','name'
                )
            )
        );
    }

    public function asInertia(Warehouse $warehouse, Request $request): RedirectResponse
    {
        $this->set('warehouse', $warehouse);
        $this->fillFromRequest($request);
        $this->validateAttributes();


        $this->handle(
            $warehouse,
            $request->only(
                'code','name'
            )
        );

        return redirect()->back();


    }
}
