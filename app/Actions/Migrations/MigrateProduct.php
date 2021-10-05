<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 14:28:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Selling\Product\StoreProduct;
use App\Actions\Selling\Product\UpdateProduct;
use App\Models\Selling\Product;
use App\Models\Selling\Shop;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateProduct
{
    use AsAction;
    use MigrateAurora;

    private function parseAuroraData($auroraData): array
    {
        $data     = [];
        $settings = [];

        $status = true;
        if ($auroraData->{'Product Status'} == 'Discontinued') {
            $status = false;
        }

        $state = match ($auroraData->{'Product Status'}) {
            'InProcess' => 'creating',
            'Discontinuing' => 'discontinuing',
            'Discontinued' => 'discontinued',
            default => 'active',
        };


        $units = $auroraData->{'Product Units Per Case'};
        if ($units == 0) {
            $units = 1;
        }

        if ($auroraData->{'Product Valid From'} == '0000-00-00 00:00:00') {
            $created_at = null;
        } else {
            $created_at = $auroraData->{'Product Valid From'};
        }

        $productData = [


            'code' => $auroraData->{'Product Code'},
            'name' => $auroraData->{'Product Name'},

            'unit_price' => $auroraData->{'Product Price'} / $units,
            'units'      => $units,

            'status' => $status,
            'state'  => $state,

            'data'       => $data,
            'settings'   => $settings,
            'created_at' => $created_at,
            'aurora_id'  => $auroraData->{'Product ID'}
        ];

        return $this->sanitizeData($productData);
    }

    public function handle($auroraData): array
    {
        $table  = 'Product Dimension';
        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];
        $shop   = Shop::withTrashed()->firstWhere('aurora_id', $auroraData->{'Product Store Key'});


        if (!$shop) {
            $result['errors']++;

            return $result;
        }

        $productData = $this->parseAuroraData($auroraData);

        $auroraImagesCollection = $this->getModelImagesCollection('Product', $auroraData->{'Product ID'});


        if ($auroraData->aiku_id) {
            $product = Product::withTrashed()->find($auroraData->aiku_id);
            if ($product) {
                $product = UpdateProduct::run($product, $productData);
                $changes  = $product->getChanges();
                if (count($changes) > 0) {
                    $result['updated']++;
                }
            } else {
                $result['errors']++;
                DB::connection('aurora')->table($table)
                    ->where('Product ID', $auroraData->{'Product ID'})
                    ->update(['aiku_id' => null]);

                return $result;
            }
        } else {
            $product = StoreProduct::run($shop, $productData);
            if (!$product) {
                $result['errors']++;

                return $result;
            }


            DB::connection('aurora')->table($table)
                ->where('Product ID', $auroraData->{'Product ID'})
                ->update(['aiku_id' => $product->id]);
            $result['inserted']++;
        }






        $auroraImagesCollectionWithImage=$auroraImagesCollection->each(function ($auroraImage)  {
            if($image = MigrateImage::run($auroraImage)){
                return $auroraImage->image_id=$image->id;
            }else{
                return $auroraImage->image_id=null;
            }

        });

        MigrateImageModels::run($product,$auroraImagesCollectionWithImage);


        return $result;
    }

}
