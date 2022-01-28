<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 29 Jan 2022 01:05:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\CustomerClient;

use App\Models\CRM\CustomerClient;
use App\Models\Utils\ActionResult;
use App\Actions\WithUpdate;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateCustomerClient
{
    use AsAction;
    use WithUpdate;

    public function handle(
        CustomerClient $customerClient,
        array $contactData,
        array $customerClientData,
    ): ActionResult {
        $res = new ActionResult();

        $customerClient->contact->update($contactData);
        $res->changes = array_merge($res->changes, $customerClient->contact->getChanges());


        $customerClient->update( Arr::except($customerClientData, ['data']));
        $customerClient->update($this->extractJson($customerClientData));


        $res->changes = array_merge($res->changes, $customerClient->getChanges());

        $res->model    = $customerClient;
        $res->model_id = $customerClient->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
