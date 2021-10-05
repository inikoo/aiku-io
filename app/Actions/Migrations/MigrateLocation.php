<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 05 Oct 2021 00:56:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Distribution\Location\StoreLocation;
use App\Actions\Distribution\Location\UpdateLocation;
use App\Models\Distribution\Location;
use App\Models\Distribution\Warehouse;
use App\Models\Distribution\WarehouseArea;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateLocation
{
    use AsAction;

    public function handle($auroraData): array
    {
        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];


        $LocationData = [
            'code'      => $auroraData->{'Location Code'},
            'aurora_id' => $auroraData->{'Location Key'},

        ];

        $parent = (new Warehouse())->firstWhere('aurora_id', $auroraData->{'Location Warehouse Key'});


        if ($auroraData->{'Location Warehouse Area Key'}) {
            $auroraWarehouseArea = DB::connection('aurora')->table('Warehouse Area Dimension')->where('Warehouse Area Key', $auroraData->{'Location Warehouse Area Key'})->first();
            //print_r($auroraWarehouseArea);
            //print_r($auroraWarehouseArea->{'Warehouse Area Place'});
            if ($auroraWarehouseArea and $auroraWarehouseArea->{'Warehouse Area Place'} == 'Local') {
                $parent = (new WarehouseArea())->firstWhere('aurora_id', $auroraData->{'Location Warehouse Area Key'});
            }
        }

        if ($auroraData->aiku_id) {
            //$LocationData['warehouse_id']=

            $location = Location::withTrashed()->find($auroraData->aiku_id);
            if ($location) {
                if (class_basename($parent::class) == 'WarehouseArea') {
                    $LocationData['warehouse_id']      = $parent->warehouse_id;
                    $LocationData['warehouse_area_id'] = $parent->id;
                } else {
                    $LocationData['warehouse_id']      = $parent->id;
                    $LocationData['warehouse_area_id'] = null;
                }

                $location = UpdateLocation::run($location, $LocationData);
                $changes  = $location->getChanges();
                if (count($changes) > 0) {
                    $result['updated']++;
                }
            } else {

                $result['errors']++;
                DB::connection('aurora')->table('Location Dimension')
                    ->where('Location Key', $auroraData->{'Location Key'})
                    ->update(['aiku_id' => null]);


                return $result;
            }
        } else {
            $location = StoreLocation::run($parent, $LocationData);

            if (!$location) {
                $result['errors']++;
                return $result;
            }

            DB::connection('aurora')->table('Location Dimension')
                ->where('Location Key', $auroraData->{'Location Key'})
                ->update(['aiku_id' => $location->id]);

            $result['inserted']++;
        }


        return $result;
    }
}
