<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 29 Mar 2022 00:42:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\HumanResources\Guest;

use App\Http\Resources\System\GuestResource;
use App\Models\HumanResources\Guest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class ShowGuests
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
        $guests = QueryBuilder::for(Guest::class)
            ->allowedFilters(['nickname'])
            ->paginate();
        return  GuestResource::collection($guests);
    }
}
