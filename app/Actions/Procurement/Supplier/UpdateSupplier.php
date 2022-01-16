<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:16:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;

use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use App\Models\Procurement\Supplier;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSupplier
{
    use AsAction;
    use WithUpdate;

    public function handle(Supplier $supplier, array $modelData, array $contactData): ActionResult
    {
        $res = new ActionResult();
        $supplier->contact()->update($contactData);
        $res->changes = array_merge($res->changes, $supplier->contact->getChanges());


        $supplier->update( Arr::except($modelData, ['data','settings']));
        $supplier->update($this->extractJson($modelData,['data','settings']));

        $res->changes = array_merge($res->changes, $supplier->getChanges());


        $res->model    = $supplier;
        $res->model_id = $supplier->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';



        return $res;
    }
}
