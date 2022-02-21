<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 30 Jan 2022 19:06:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\HumanResources\Employee;
use Illuminate\Support\Collection;


class HydrateEmployee extends HydrateModel
{

    public string $commandSignature = 'hydrate:employee {id} {--t|tenant=* : Tenant code}';

    public function handle(Employee $employee): void
    {

    }

    protected function getModel(int $id): Employee
    {
        return Employee::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Employee::withTrashed()->get();
    }

}


