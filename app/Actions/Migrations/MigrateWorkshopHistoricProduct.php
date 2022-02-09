<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 03:32:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Production\HistoricWorkshopProduct\StoreHistoricWorkshopProduct;
use App\Actions\Production\HistoricWorkshopProduct\UpdateHistoricWorkshopProduct;

use App\Models\Procurement\HistoricSupplierProduct;
use App\Models\Production\WorkshopProduct;

use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateWorkshopHistoricProduct extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Part Historic Dimension';
        $this->auModel->id_field = 'Supplier Part Historic Key';
        $this->aiku_id_field     = 'aiku_workshop_historic_product_id';
    }

    public function parseModelData()
    {
        $status = 0;
        if (DB::connection('aurora')->table('Supplier Part Dimension')->where('Supplier Part Historic Key', '=', $this->auModel->data->{'Supplier Part Historic Key'})->exists()) {
            $status = 1;
        }


        $this->modelData = $this->sanitizeData(
            [
                'code'        => $this->auModel->data->{'Supplier Part Historic Reference'},
                'pack'        => $this->auModel->data->{'Supplier Part Historic Units Per Package'},
                'carton'      => $this->auModel->data->{'Supplier Part Historic Units Per Package'} * $this->auModel->data->{'Supplier Part Historic Packages Per Carton'},
                'status'      => $status,
                'created_at'  => null,
                'aurora_id'   => $this->auModel->data->{'Supplier Part Historic Key'}
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Supplier Part Historic Key'};
    }


    public function setModel()
    {
        $this->model = HistoricSupplierProduct::withTrashed()->find($this->auModel->data->aiku_workshop_historic_product_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateHistoricWorkshopProduct::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreHistoricWorkshopProduct::run(workshopProduct: $this->parent, data: $this->modelData);
    }

    public function getParent(): WorkshopProduct
    {
        return WorkshopProduct::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Supplier Part Historic Supplier Part Key'});
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
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
