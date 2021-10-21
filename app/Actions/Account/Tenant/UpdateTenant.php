<?php

namespace App\Actions\Account\Tenant;

use App\Models\Account\Tenant;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTenant
{
    use AsAction;

    public function handle(Tenant $tenant,array $data): Tenant
    {
         $tenant->update($data);
         return $tenant;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root') || $request->user()->tokenCan('system:edit');
    }

    public function rules(): array
    {
        return [
            'nickname' => 'sometimes|required|string|unique:tenants',
            'email' => 'sometimes|required|email|unique:tenants',

        ];
    }


    public function asController(Tenant $tenant, ActionRequest $request): Tenant
    {

        return $this->handle(
            $tenant,
            $request->only('nickname', 'email'),
        );
    }
}
