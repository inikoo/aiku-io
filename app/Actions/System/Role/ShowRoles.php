<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 21:34:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Role;


use App\Http\Resources\System\RoleResource;

use App\Models\System\Role;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class ShowRoles
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
        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters(['name'])
            ->paginate();
        return  RoleResource::collection($roles);
    }
}
