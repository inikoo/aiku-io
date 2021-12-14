<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:19:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\System\Guest;

use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\System\Guest;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowGuest
{
    use AsAction;

    public function handle(Guest $guest): Guest
    {
        return $guest;
    }

    #[Pure] public function jsonResponse(Guest $guest): EmployeeResource
    {
        return new EmployeeResource($guest);
    }
}
