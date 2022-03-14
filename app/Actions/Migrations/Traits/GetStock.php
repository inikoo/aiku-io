<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Mar 2022 02:13:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateDeletedStock;
use App\Actions\Migrations\MigrateStock;
use App\Models\Inventory\Stock;
use Illuminate\Support\Facades\DB;

trait GetStock
{


    public function getStock($auroraId): ?Stock
    {
        $stock = Stock::withTrashed()->firstWhere('aurora_id', $auroraId);
        if (!$stock) {
            foreach (
                DB::connection('aurora')
                    ->table('Part Dimension')
                    ->where('Part SKU', $auroraId)
                    ->get() as $auroraData
            ) {
                $_res  = MigrateStock::run($auroraData);
                $stock = $_res->model;
            }

            if (!$stock) {
                foreach (
                    DB::connection('aurora')
                        ->table('Part Deleted Dimension')
                        ->where('Part Deleted Key', $auroraId)
                        ->get() as $auroraData
                ) {
                    $_res  = MigrateDeletedStock::run($auroraData);
                    $stock = $_res->model;
                }
            }
        }

        return $stock;
    }

}


