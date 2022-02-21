<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 18:27:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Account\Tenant;


use App\Http\Resources\Account\TenantResource;
use App\Models\Account\Tenant;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class ShowTenants
{
    use AsAction;

    public function handle()
    {
        // ...
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');

    }

     public function jsonResponse():AnonymousResourceCollection
    {
        $tenants = QueryBuilder::for(Tenant::class)
            ->allowedFilters(['code', 'domain','database'])
            ->paginate();
        return  TenantResource::collection($tenants);
    }
}
