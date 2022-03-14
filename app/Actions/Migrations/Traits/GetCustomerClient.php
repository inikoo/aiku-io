<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:20:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateCustomerClient;
use App\Models\CRM\CustomerClient;
use Illuminate\Support\Facades\DB;

trait GetCustomerClient
{


    public function getCustomerClient($auroraCustomerClientId): ?CustomerClient
    {
        $customerClient = CustomerClient::withTrashed()->firstWhere('aurora_id', $auroraCustomerClientId);

        if (!$customerClient) {
            foreach (
                DB::connection('aurora')
                    ->table('Customer Client Dimension')
                    ->where('Customer Client Key', $auroraCustomerClientId)
                    ->get() as $auroraModel
            ) {

                $res=MigrateCustomerClient::run($auroraModel);
                $customerClient=$res->model;

            }
        }

        return $customerClient;
    }

}


