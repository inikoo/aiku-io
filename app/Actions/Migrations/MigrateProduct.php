<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 14:28:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Trade\Product\StoreProduct;
use App\Actions\Trade\Product\UpdateProduct;
use App\Models\Trade\Product;
use App\Models\Trade\Shop;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateProduct extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Product Dimension';
        $this->auModel->id_field = 'Product ID';
    }

    public function parseModelData()
    {
        $data     = [];
        $settings = [];

        $status = 1;
        if ($this->auModel->data->{'Product Status'} == 'Discontinued') {
            $status = 0;
        }

        $state = match ($this->auModel->data->{'Product Status'}) {
            'InProcess' => 'creating',
            'Discontinuing' => 'discontinuing',
            'Discontinued' => 'discontinued',
            default => 'active',
        };


        $units = $this->auModel->data->{'Product Units Per Case'};
        if ($units == 0) {
            $units = 1;
        }

        if ($this->auModel->data->{'Product Valid From'} == '0000-00-00 00:00:00') {
            $created_at = null;
        } else {
            $created_at = $this->auModel->data->{'Product Valid From'};
        }

        $unit_price=$this->auModel->data->{'Product Price'} / $units;
        $data['raw_price']=$unit_price;


        $this->modelData = $this->sanitizeData(
            [
                'code' => $this->auModel->data->{'Product Code'},
                'name' => $this->auModel->data->{'Product Name'},

                'price' => round($unit_price,2),
                'outer' => $units,

                'status' => $status,
                'state'  => $state,

                'data'       => $data,
                'settings'   => $settings,
                'created_at' => $created_at,
                'aurora_product_id'  => $this->auModel->data->{'Product ID'}
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Product ID'};
    }

    protected function migrateImages()
    {
        $images = $this->getModelImagesCollection(
            'Product',
            $this->auModel->data->{'Product ID'}
        )->each(function ($auroraImage) {
            if ($image = MigrateImage::run($auroraImage)) {
                return $auroraImage->image_id = $image->id;
            } else {
                return $auroraImage->image_id = null;
            }
        });


        MigrateImageModels::run($this->model, $images);
    }

    public function setModel()
    {
        $this->model = Product::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel():MigrationResult
    {
        return UpdateProduct::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreProduct::run($this->parent, $this->modelData);

    }

    public function getParent(): Shop|null
    {
        return Shop::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Product Store Key'});
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Product Dimension')->where('Product Id', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res  = new MigrationResult();
        $res->errors[]='Aurora model not found';
        $res->status='error';
        return $res;
    }

}
