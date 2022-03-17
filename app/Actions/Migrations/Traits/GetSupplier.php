<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 22:29:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateDeletedWorkshop;
use App\Actions\Migrations\MigrateSupplier;
use App\Actions\Migrations\MigrateDeletedSupplier;
use App\Models\Procurement\Supplier;
use App\Models\Production\Workshop;
use Illuminate\Support\Facades\DB;

trait GetSupplier
{


    public function getSupplier($auroraModelId): Supplier|Workshop|null
    {
        $supplier = Supplier::withTrashed()->firstWhere('aurora_id', $auroraModelId);

        if (!$supplier) {
            foreach (
                DB::connection('aurora')
                    ->table('Supplier Dimension')
                    ->where('Supplier Key', $auroraModelId)
                    ->get() as $auroraModel
            ) {
                $_res = MigrateSupplier::run($auroraModel);

                $supplier = $_res->model;


            }
        }

        if (!$supplier) {
            foreach (
                DB::connection('aurora')
                    ->table('Supplier Deleted Dimension')
                    ->where('Supplier Deleted Key', $auroraModelId)
                    ->get() as $auData
            ) {
                $auroraDeletedData = json_decode(gzuncompress($auData->{'Supplier Deleted Metadata'}));
                if (isset($auroraDeletedData->{'Supplier Production'}) and $auroraDeletedData->{'Supplier Production'} == 'Yes') {
                    $_res = MigrateDeletedWorkshop::run($auData);
                } else {
                    $_res = MigrateDeletedSupplier::run($auData);
                }
                $supplier = $_res->model;
            }
        }


        return $supplier;
    }

}


