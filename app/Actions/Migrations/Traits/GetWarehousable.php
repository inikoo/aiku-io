<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Mar 2022 03:02:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateWarehouse;
use App\Actions\Migrations\MigrateWarehouseArea;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Illuminate\Support\Facades\DB;

trait GetWarehousable
{


    public function getWarehousable($auroraId): null|WarehouseArea|Warehouse
    {
        $auroraWarehouseArea = DB::connection('aurora')->table('Warehouse Area Dimension')->where('Warehouse Area Key', $auroraId)->first();
        if ($auroraWarehouseArea and $auroraWarehouseArea->{'Warehouse Area Place'} == 'Local') {
            $warehousable = (new WarehouseArea())->firstWhere('aurora_id', $auroraId);
        } else {
            $warehousable = (new Warehouse())->firstWhere('aurora_id', $auroraId);
        }

        if (!$warehousable) {
            foreach (
                DB::connection('aurora')
                    ->table('Warehouse Area Dimension')
                    ->where('Warehouse Area Key', $auroraId)
                    ->get() as $auroraModel
            ) {
                if ($auroraModel->{'Warehouse Area Place'} == 'Local') {
                    $res = MigrateWarehouseArea::run($auroraModel);
                } else {
                    $auroraModel->{'Warehouse Name'} = $auroraModel->{'Warehouse Area Name'};
                    $auroraModel->{'Warehouse Code'} = $auroraModel->{'Warehouse Area Code'};
                    $auroraModel->{'Warehouse Key'}  = $auroraModel->{'Warehouse Area Key'};

                    $res = MigrateWarehouse::run($auroraModel);
                }
                $warehousable = $res->model;
            }
        }

        return $warehousable;
    }

}


