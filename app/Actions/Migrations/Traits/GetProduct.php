<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:12:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;

use App\Actions\Migrations\MigrateHistoricProduct;
use App\Actions\Migrations\MigrateProduct;
use App\Models\Marketing\Product;
use Illuminate\Support\Facades\DB;

trait GetProduct
{


    public function getProduct($auroraId): ?Product
    {
        $product = Product::withTrashed()->firstWhere('aurora_id', $auroraId);
        if (!$product) {

            foreach (
                DB::connection('aurora')
                    ->table('Product Dimension')
                    ->where('Product ID', $auroraId)
                    ->get() as $auroraData
            ) {
                $_res=MigrateProduct::run($auroraData);
                $product=$_res->model;
                foreach (DB::connection('aurora')->table('Product History Dimension')->where('Product ID',$auroraId)->get() as $auroraHistoricData) {
                    MigrateHistoricProduct::run($auroraHistoricData);

                }
            }
        }

        return $product;
    }

}


