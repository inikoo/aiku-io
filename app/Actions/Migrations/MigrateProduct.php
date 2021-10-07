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
use JetBrains\PhpStorm\Pure;

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

        $status = true;
        if ($this->auModel->data->{'Product Status'} == 'Discontinued') {
            $status = false;
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


        $this->modelData = $this->sanitizeData(
            [
                'code' => $this->auModel->data->{'Product Code'},
                'name' => $this->auModel->data->{'Product Name'},

                'unit_price' => $this->auModel->data->{'Product Price'} / $units,
                'units'      => $units,

                'status' => $status,
                'state'  => $state,

                'data'       => $data,
                'settings'   => $settings,
                'created_at' => $created_at,
                'aurora_id'  => $this->auModel->data->{'Product ID'}
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

    public function updateModel()
    {
        $this->model = UpdateProduct::run($this->model, $this->modelData);
    }

    public function storeModel(): ?int
    {
        $product     = StoreProduct::run($this->parent, $this->modelData);
        $this->model = $product;

        return $product?->id;
    }

    public function getParent(): Shop|null
    {
        return Shop::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Product Store Key'});
    }

}
