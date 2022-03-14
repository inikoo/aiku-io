<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 11 Mar 2022 16:50:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\Customer\Reminders\SyncReminders;
use App\Actions\Migrations\Traits\GetProduct;
use App\Models\CRM\Customer;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateReminders
{
    use AsAction;
    use GetProduct;

    public function handle(Customer $customer): ActionResult
    {
        $products = [];
        foreach (
            DB::connection('aurora')
                ->table('Back in Stock Reminder Fact')
                ->where('Back in Stock Reminder Customer Key', $customer->aurora_id)->get() as $auroraReminders
        ) {

            $product= $this->getProduct($auroraReminders->{'Back in Stock Reminder Product ID'});


            if ($product) {
                $products[$product->id] =
                    [
                        'created_at' => $auroraReminders->{'Back in Stock Reminder Creation Date'},
                        'aurora_id'  => $auroraReminders->{'Back in Stock Reminder Key'},
                    ];
            }
        }


        return SyncReminders::run($customer,$products);
    }
}
