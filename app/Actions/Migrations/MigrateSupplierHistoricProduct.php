<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 12 Oct 2021 18:16:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Trade\HistoricProduct\StoreHistoricProduct;
use App\Actions\Trade\HistoricProduct\UpdateHistoricProduct;
use App\Models\Trade\HistoricProduct;
use App\Models\Trade\Product;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateSupplierHistoricProduct extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Part Historic Dimension';
        $this->auModel->id_field = 'Supplier Part Historic Key';
    }

    public function parseModelData()
    {
        $status = 0;
        if (DB::connection('aurora')->table('Supplier Part Dimension')->where('Supplier Part Historic Key', '=', $this->auModel->data->{'Supplier Part Historic Key'})->exists()) {
            $status = 1;
        }


        $this->modelData = $this->sanitizeData(
            [
                'code'                       => $this->auModel->data->{'Supplier Part Historic Reference'},
                'price'                      => round($this->auModel->data->{'Supplier Part Historic Unit Cost'} ?? 0,4),
                'pack'                       => $this->auModel->data->{'Supplier Part Historic Units Per Package'},
                'carton'                     => $this->auModel->data->{'Supplier Part Historic Units Per Package'} * $this->auModel->data->{'Supplier Part Historic Packages Per Carton'},
                'cbm'                        => round($this->auModel->data->{'Supplier Part Historic Carton CBM'},4),
                'currency_id'                => $this->parseCurrencyID($this->auModel->data->{'Supplier Part Historic Currency Code'}),
                'status'                     => $status,
                'created_at'                 => null,
                'aurora_supplier_product_id' => $this->auModel->data->{'Supplier Part Historic Key'}
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Supplier Part Historic Key'};
    }


    public function setModel()
    {
        $this->model = HistoricProduct::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateHistoricProduct::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreHistoricProduct::run(product: $this->parent, data: $this->modelData);

    }

    public function getParent(): Product
    {
        return Product::withTrashed()->firstWhere('aurora_supplier_product_id', $this->auModel->data->{'Supplier Part Historic Supplier Part Key'});
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Supplier Part Historic Dimension')->where('Supplier Part Historic Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res  = new ActionResult();
        $res->errors[]='Aurora model not found';
        $res->status='error';
        return $res;
    }


}
