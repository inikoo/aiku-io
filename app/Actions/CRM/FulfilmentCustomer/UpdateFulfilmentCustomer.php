<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 13:05:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\FulfilmentCustomer;

use App\Models\CRM\FulfilmentCustomer;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateFulfilmentCustomer
{
    use AsAction;
    use WithUpdate;

    public function handle(
        FulfilmentCustomer $fulfilmentCustomer,
        array $fulfilmentCustomerData,
    ): ActionResult {
        $res = new ActionResult();

        $fulfilmentCustomer->update( Arr::except($fulfilmentCustomerData, ['data']));
        //$fulfilmentCustomer->update($this->extractJson($fulfilmentCustomerData));


        $res->changes = array_merge($res->changes, $fulfilmentCustomer->getChanges());

        $res->model    = $fulfilmentCustomer;
        $res->model_id = $fulfilmentCustomer->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
