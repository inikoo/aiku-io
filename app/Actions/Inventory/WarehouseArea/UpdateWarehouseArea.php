<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 12:35:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Inventory\WarehouseArea;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Inventory\WarehouseArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

/**
 * @property WarehouseArea $warehouseArea
 */
class UpdateWarehouseArea
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(WarehouseArea $area, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $area->update(Arr::except($modelData, ['data']));
        $area->update($this->extractJson($modelData));
        $res->changes = array_merge($res->changes, $area->getChanges());

        $res->model    = $area;
        $res->model_id = $area->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root')
            || $request->user()->tokenCan('inventory:edit')
            || $request->user()->hasPermissionTo("warehouses.edit.{$this->warehouseArea->warehouse_id}");
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|required|alpha_dash|unique:App\Models\Inventory\WarehouseArea,code',
            'name' => 'sometimes|required|unique:App\Models\Inventory\WarehouseArea,name',

        ];
    }

    public function asController(WarehouseArea $warehouseArea, ActionRequest $request): ActionResultResource
    {
        $request->validate();


        return new ActionResultResource(
            $this->handle(
                $warehouseArea,
                $request->only(
                    'code','name'
                )
            )
        );
    }

    public function asInertia(WarehouseArea $warehouseArea, Request $request): RedirectResponse
    {
        $this->set('warehouseArea', $warehouseArea);
        $this->fillFromRequest($request);
        $this->validateAttributes();


        $this->handle(
            $warehouseArea,
            $request->only(
                'code','name'
            )
        );

        return redirect()->back();


    }

}

