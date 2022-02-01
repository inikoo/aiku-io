<?php

namespace App\Actions\Inventory\Location;

use App\Http\Resources\Utils\ActionResultResource;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Inventory\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

/**
 * @property Location $location
 */
class UpdateLocation
{
    use AsAction;
    use WithUpdate;
    use WithAttributes;

    public function handle(Location $location, array $modelData): ActionResult
    {
        $res = new ActionResult();

        $location->update(Arr::except($modelData, ['data']));
        $location->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $location->getChanges());

        $res->model    = $location;
        $res->model_id = $location->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root')
            || $request->user()->tokenCan('inventory:edit')
            || $request->user()->hasPermissionTo("warehouses.edit.{$this->location->warehouse_id}");
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|required|alpha_dash|unique:App\Models\Inventory\Location,code',

        ];
    }

    public function asController(Location $location, ActionRequest $request): ActionResultResource
    {
        $request->validate();


        return new ActionResultResource(
            $this->handle(
                $location,
                $request->only(
                    'code',
                )
            )
        );
    }

    public function asInertia(Location $location, Request $request): RedirectResponse
    {
        $this->set('location', $location);
        $this->fillFromRequest($request);
        $this->validateAttributes();


        $this->handle(
            $location,
            $request->only(
                'code',
            )
        );

        return redirect()->back();


    }
}
