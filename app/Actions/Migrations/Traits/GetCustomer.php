<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:13:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateCustomer;
use App\Actions\Migrations\MigrateCustomerClient;
use App\Models\CRM\Customer;
use Illuminate\Support\Facades\DB;

trait GetCustomer
{


    public function getCustomer($auroraCustomerId): ?Customer
    {
        $customer = Customer::withTrashed()->firstWhere('aurora_id', $auroraCustomerId);

        if (!$customer) {
            foreach (
                DB::connection('aurora')
                    ->table('Customer Dimension')
                    ->where('Customer Key', $auroraCustomerId)
                    ->get() as $auroraModel
            ) {
                $_res     = MigrateCustomer::run($auroraModel);
                $customer = $_res->model;

                foreach (DB::connection('aurora')->table('Customer Client Dimension')->where('Customer Client Customer Key', $auroraCustomerId)->get() as $auroraCustomerClientData) {
                    MigrateCustomerClient::run($auroraCustomerClientData);
                }
            }
        }

        return $customer;
    }

}


