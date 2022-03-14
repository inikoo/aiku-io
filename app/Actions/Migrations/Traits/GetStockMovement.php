<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:37:28 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;


use App\Actions\Migrations\MigrateStockMovement;
use App\Models\Inventory\StockMovement;
use Illuminate\Support\Facades\DB;

trait GetStockMovement
{


    public function getStockMovement($auroraId): ?StockMovement
    {
        $stockMovement = StockMovement::firstWhere('aurora_id', $auroraId);

        if (!$stockMovement) {

            foreach (
                DB::connection('aurora')
                    ->table('Inventory Transaction Fact')
                    ->where('Inventory Transaction Record Type','Movement')
                    ->whereNotIn('Inventory Transaction Type',['Adjust'])
                    ->where('Inventory Transaction Key',$auroraId)
                    ->get() as $auroraData
            ) {
                $_res=MigrateStockMovement::run($auroraData);
                $stockMovement=$_res->model;

            }


        }
        return $stockMovement;
    }

}


