<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Mar 2022 02:45:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateDeletedLocation;
use App\Actions\Migrations\MigrateLocation;
use App\Models\Inventory\Location;
use Illuminate\Support\Facades\DB;

trait GetLocation
{


    public function getLocation($auroraId): ?Location
    {
        $location = Location::withTrashed()->firstWhere('aurora_id', $auroraId);
        if (!$location) {
            foreach (
                DB::connection('aurora')
                    ->table('Location Dimension')
                    ->where('Location Key', $auroraId)
                    ->get() as $auroraData
            ) {
                $_res  = MigrateLocation::run($auroraData);
                $location = $_res->model;
            }

            if (!$location) {
                foreach (
                    DB::connection('aurora')
                        ->table('Location Deleted Dimension')
                        ->where('Location Deleted Key', $auroraId)
                        ->get() as $auroraData
                ) {
                    $_res  = MigrateDeletedLocation::run($auroraData);
                    $location = $_res->model;
                }
            }
        }

        return $location;
    }

}


