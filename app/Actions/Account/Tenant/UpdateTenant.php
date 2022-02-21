<?php

namespace App\Actions\Account\Tenant;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Account\Tenant;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTenant
{
    use AsAction;
    use WithUpdate;

    public function handle(Tenant $tenant,array $modelData): ActionResult
    {
        $res = new ActionResult();

        $tenant->update( Arr::except($modelData, ['data']));
        $tenant->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $tenant->getChanges());

        $res->model    = $tenant;
        $res->model_id = $tenant->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('system:edit');
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|required|string|unique:tenants',
            'email' => 'sometimes|required|email|unique:tenants',

        ];
    }


    public function asController(Tenant $tenant, ActionRequest $request): ActionResult
    {

        return $this->handle(
            $tenant,
            $request->only('code', 'email'),
        );
    }
}
