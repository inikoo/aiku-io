<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 11 Mar 2022 18:32:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer\Reminders;

use App\Models\Utils\ActionResult;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncReminders
{
    use AsAction;

    public function handle(
        Customer $customer,
        array $productsData,
    ): ActionResult {
        $res = new ActionResult();

        $changes = $customer->reminders()->sync($productsData);

        if (count($changes['attached']) || count($changes['detached']) || count($changes['updated'])) {
            $res->status = 'updated';
            $res->changes  = $changes;
        }

        $res->model    = $customer;
        $res->model_id = $customer->id;

        return $res;
    }
}
