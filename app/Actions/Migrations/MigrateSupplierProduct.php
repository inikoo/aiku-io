<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 12 Oct 2021 00:23:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Trade\Product\StoreProduct;
use App\Actions\Trade\Product\UpdateProduct;
use App\Models\Trade\Product;
use App\Models\Buying\Supplier;
use App\Models\Trade\TradeUnit;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

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
        $this->auModel->partData = DB::connection('aurora')
            ->table('Part Dimension')
            ->where('Part SKU', $this->auModel->data->{'Supplier Part Part SKU'})->first();


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

        $data['raw_price'] = $this->auModel->data->{'Supplier Part Unit Cost'} ?? 0;

        $this->modelData = $this->sanitizeData(
            [
                'code' => $this->auModel->data->{'Supplier Part Reference'},
                'name' => $this->auModel->data->{'Supplier Part Description'},

                'price'  => round($this->auModel->data->{'Supplier Part Unit Cost'} ?? 0, 2),
                'pack'   => $this->auModel->partData->{'Part Units Per Package'},
                'carton' => $this->auModel->data->{'Supplier Part Packages Per Carton'} * $this->auModel->partData->{'Part Units Per Package'},


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

    public function updateModel(): ActionResult
    {
        return UpdateProduct::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreProduct::run(vendor: $this->parent, data: $this->modelData);
    }

    public function getParent(): Supplier
    {
        return Supplier::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Supplier Part Supplier Key'});
    }

    public function postMigrateActions(ActionResult $res): ActionResult
    {
        $tradeResult = MigrateTradeUnit::run($this->auModel->partData);

        $res->changes = array_merge($res->changes, $tradeResult->changes);


        if ($res->status == 'unchanged') {
            $res->status = $res->changes ? 'updated' : 'unchanged';
        }
        $tradeUnit = TradeUnit::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Supplier Part Part SKU'});

        /** @var Product $product */
        $product = $this->model;

        $product->tradeUnits()->sync([$tradeUnit->id => ['quantity' => 1]]);


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Supplier Part Dimension')->where('Supplier Part Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}
