<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Mar 2022 23:37:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\HumanResources\Guest;
use App\Models\HumanResources\WorkTarget;
use Illuminate\Support\Collection;

class HydrateWorkTarget extends HydrateModel
{

    public string $commandSignature = 'hydrate:work_target {id} {--t|tenant=* : Tenant code}';


    public function handle(WorkTarget $workTarget): void
    {

        $numberInvoices = $workTarget->invoices->count();
        $stats = [
            'number_invoices' => $numberInvoices,
        ];

    }


    protected function getModel(int $id): Guest
    {
        return WorkTarget::find($id);
    }

    protected function getAllModels(): Collection
    {
        return WorkTarget::all();
    }

}


