<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 12 Oct 2021 00:23:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Helpers\Product\StoreProduct;
use App\Actions\Helpers\Product\UpdateProduct;
use App\Models\Helpers\Product;
use App\Models\Buying\Supplier;
use JetBrains\PhpStorm\Pure;

class MigrateSupplierProduct extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Part Dimension';
        $this->auModel->id_field = 'Supplier Part Key';
    }

    public function parseModelData()
    {
        $data     = [];
        $settings = [];

        $status = 1;
        if ($this->auModel->data->{'Supplier Part Status'} == 'Discontinued') {
            $status = 0;
        }
        $state = match ($this->auModel->data->{'Supplier Part Status'}) {
            'NoAvailable' => 'no-available',

            'Discontinued' => 'discontinued',
            default => 'active',
        };


        if ($this->auModel->data->{'Supplier Part From'} == '0000-00-00 00:00:00') {
            $created_at = null;
        } else {
            $created_at = $this->auModel->data->{'Supplier Part From'};
        }


        $this->modelData = $this->sanitizeData(
            [
                'code' => $this->auModel->data->{'Supplier Part Reference'},
                'name' => $this->auModel->data->{'Supplier Part Description'},

                'price'  => $this->auModel->data->{'Supplier Part Unit Cost'} ?? 0,
                'pack'   => $this->auModel->data->{'Part Units Per Package'},
                'carton' => $this->auModel->data->{'Supplier Part Packages Per Carton'} * $this->auModel->data->{'Part Units Per Package'},


                'status' => $status,
                'state'  => $state,

                'data'                       => $data,
                'settings'                   => $settings,
                'created_at'                 => $created_at,
                'aurora_supplier_product_id' => $this->auModel->data->{'Supplier Part Key'}
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Supplier Part Key'};
    }

    protected function migrateImages()
    {
        $images = $this->getModelImagesCollection(
            'Supplier Part',
            $this->auModel->data->{'Supplier Part Key'}
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
        $product     = StoreProduct::run(vendor: $this->parent, data: $this->modelData);
        $this->model = $product;

        return $product?->id;
    }

    public function getParent(): Supplier
    {
        return Supplier::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Supplier Part Supplier Key'});
    }

}
