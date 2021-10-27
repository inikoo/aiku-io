<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 14:28:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Helpers\HistoricProduct\StoreHistoricProduct;
use App\Actions\Helpers\HistoricProduct\UpdateHistoricProduct;
use App\Models\Helpers\HistoricProduct;
use App\Models\Helpers\Product;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateHistoricProduct extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Product History Dimension';
        $this->auModel->id_field = 'Product Key';
    }

    public function parseModelData()
    {
        $deleted_at = $this->auModel->data->{'Product History Valid To'};

        $status = 0;
        if (DB::connection('aurora')->table('Product Dimension')->where('Product Current Key', '=', $this->auModel->data->{'Product Key'})->exists()) {
            $status     = 1;
            $deleted_at = null;
        }


        $units = $this->auModel->data->{'Product History Units Per Case'};
        if ($units == 0) {
            $units = 1;
        }

        $this->modelData = $this->sanitizeData(
            [
                'code' => $this->auModel->data->{'Product History Code'},
                'name' => $this->auModel->data->{'Product History Name'},

                'price' => $this->auModel->data->{'Product History Price'} / $units,
                'outer' => $units,

                'status' => $status,

                'created_at'        => $this->auModel->data->{'Product History Valid From'},
                'deleted_at'        => $deleted_at,
                'aurora_product_id' => $this->auModel->data->{'Product Key'}
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Product ID'};
    }


    public function setModel()
    {
        $this->model = HistoricProduct::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateHistoricProduct::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreHistoricProduct::run($this->parent, $this->modelData);
    }

    public function getParent(): Product
    {
        return Product::withTrashed()->firstWhere('aurora_product_id', $this->auModel->data->{'Product ID'});
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Product History Dimension')->where('Product Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}
