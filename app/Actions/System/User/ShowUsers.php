<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 20 Oct 2021 16:29:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\User;


use App\Http\Resources\System\UserResource;
use App\Models\System\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class ShowUsers
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
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['username', 'status'])
            ->paginate();
        return  UserResource::collection($users);
    }
}
