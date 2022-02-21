<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 19:06:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\System\Guest;
use Illuminate\Support\Collection;

class HydrateGuest extends HydrateModel
{

    public string $commandSignature = 'hydrate:guest {id} {--t|tenant=* : Tenant code}';


    public function handle(Guest $guest): void
    {

    }


    protected function getModel(int $id): Guest
    {
        return Guest::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Guest::withTrashed()->get();
    }

}


