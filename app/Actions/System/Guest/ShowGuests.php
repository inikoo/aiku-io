<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:31:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Http\Resources\System\GuestResource;
use App\Models\System\Guest;
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
