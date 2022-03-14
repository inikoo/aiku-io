<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Mar 2022 03:36:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;


use App\Actions\Migrations\MigrateWarehouse;
use App\Models\Inventory\Warehouse;
use Illuminate\Support\Facades\DB;

trait GetWarehouse
{


    public function getWarehouse($auroraId): ?Warehouse
    {
        $warehouse = Warehouse::withTrashed()->firstWhere('aurora_id', $auroraId);
        if (!$warehouse) {

            foreach (
                DB::connection('aurora')
                    ->table('Warehouse Dimension')
                    ->where('Warehouse Key', $auroraId)
                    ->get() as $auroraData
            ) {
                $_res=MigrateWarehouse::run($auroraData);
                $warehouse=$_res->model;

            }
        }

        return $warehouse;
    }

}


