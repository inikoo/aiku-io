<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 01:24:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Distribution\Warehouse\StoreWarehouse;
use App\Actions\Distribution\Warehouse\UpdateWarehouse;
use App\Models\Distribution\Warehouse;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateWarehouse
{
    use AsAction;
    use MigrateAurora;

    public function handle($auroraData): array
    {


        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];
        $warehouseData = [
            'name'       => $auroraData->{'Warehouse Name'},
            'code'       => strtolower($auroraData->{'Warehouse Code'}),
            'aurora_id'  => $auroraData->{'Warehouse Key'},
            'created_at' => $this->getDate($auroraData->{'Warehouse Valid From'}??null),
        ];

        $warehouseData = $this->sanitizeData($warehouseData);


        if ($auroraData->aiku_id) {
            $warehouse = Warehouse::find($auroraData->aiku_id);

            if ($warehouse) {
                $warehouse = UpdateWarehouse::run($warehouse, $warehouseData);


                $changes = $warehouse->getChanges();
                if (count($changes) > 0) {
                    $result['updated']++;
                }
            } else {

                $result['errors']++;
                if(isset($auroraData->{'Warehouse Area Key'})){
                    DB::connection('aurora')->table('Warehouse Dimension')
                        ->where('Warehouse Area Key', $auroraData->{'Warehouse Area Key'})
                        ->update(['aiku_id' => null]);
                }else{
                    DB::connection('aurora')->table('Warehouse Dimension')
                        ->where('Warehouse Key', $auroraData->{'Warehouse Key'})
                        ->update(['aiku_id' => null]);
                }

            }
        } else {
            try {
                $warehouse = StoreWarehouse::run($warehouseData);

                if(isset($auroraData->{'Warehouse Area Key'})){
                    DB::connection('aurora')->table('Warehouse Area Dimension')
                        ->where('Warehouse Area Key', $auroraData->{'Warehouse Area Key'})
                        ->update(['aiku_id' => $warehouse->id]);
                }else{
                    DB::connection('aurora')->table('Warehouse Dimension')
                        ->where('Warehouse Key', $auroraData->{'Warehouse Key'})
                        ->update(['aiku_id' => $warehouse->id]);
                }

                $result['inserted']++;
            } catch (Exception $e) {
                print $e->getMessage();
                $result['errors']++;
            }
        }

        return $result;
    }
}
