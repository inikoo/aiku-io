<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:59:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Distribution\WarehouseArea\StoreWarehouseArea;
use App\Actions\Distribution\WarehouseArea\UpdateWarehouseArea;
use App\Models\Distribution\Warehouse;
use App\Models\Distribution\WarehouseArea;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateWarehouseArea
{
    use AsAction;

    public function handle($auroraData)
    {
        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];


        if ($auroraData->{'Warehouse Area Place'} == 'Local') {
            $warehouse = (new Warehouse())->firstWhere('aurora_id', $auroraData->{'Warehouse Area Warehouse Key'});
            if (!$warehouse) {
                $result['errors']++;

                return $result;
            }

            $warehouseAreaData = [
                'name'      => $auroraData->{'Warehouse Area Name'} ?? 'Name not set',
                'code'      => $auroraData->{'Warehouse Area Code'},
                'aurora_id' => $auroraData->{'Warehouse Area Key'},

            ];


            if ($auroraData->aiku_id) {
                $warehouseArea = WarehouseArea::withTrashed()->find($auroraData->aiku_id);
                if ($warehouseArea) {
                    $warehouseArea = UpdateWarehouseArea::run($warehouseArea, $warehouseAreaData);
                    $changes       = $warehouseArea->getChanges();
                    if (count($changes) > 0) {
                        $result['updated']++;
                    }
                } else {
                    $result['errors']++;

                    DB::connection('aurora')->table('Warehouse Area Dimension')
                        ->where('Warehouse Area Key', $auroraData->{'Warehouse Area Key'})
                        ->update(['aiku_id' => null]);

                    return $result;
                }
            } else {
                $warehouseArea = StoreWarehouseArea::run($warehouse, $warehouseAreaData);
                if (!$warehouseArea) {
                    $result['errors']++;

                    return $result;
                }

                DB::connection('aurora')->table('Warehouse Area Dimension')
                    ->where('Warehouse Area Key', $auroraData->{'Warehouse Area Key'})
                    ->update(['aiku_id' => $warehouseArea->id]);

                $result['inserted']++;
            }

            return $result;
        } else {
            $auroraData->{'Warehouse Name'} = $auroraData->{'Warehouse Area Name'};
            $auroraData->{'Warehouse Code'} = $auroraData->{'Warehouse Area Code'};
            $auroraData->{'Warehouse Key'}  = $auroraData->{'Warehouse Area Key'};

            return MigrateWarehouse::run($auroraData);
        }
    }
}
