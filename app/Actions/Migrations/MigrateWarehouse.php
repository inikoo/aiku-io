<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 01:24:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Inventory\Warehouse\StoreWarehouse;
use App\Actions\Inventory\Warehouse\UpdateWarehouse;
use App\Models\Inventory\Warehouse;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateWarehouse extends MigrateModel
{


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Warehouse Dimension';
        $this->auModel->id_field = 'Warehouse Key';
    }


    public function parseModelData()
    {
        $this->modelData = $this->sanitizeData(
            [
                'name'       => $this->auModel->data->{'Warehouse Name'},
                'code'       => strtolower($this->auModel->data->{'Warehouse Code'}),
                'aurora_id'  => $this->auModel->data->{'Warehouse Key'},
                'created_at' => $this->auModel->data->{'Warehouse Valid From'} ?? null,
            ]
        );
        if (isset($this->auModel->data->{'Warehouse Area Key'})) {
            $this->auModel->id       = $this->auModel->data->{'Warehouse Area Key'};
            $this->auModel->table    = 'Warehouse Area Dimension';
            $this->auModel->id_field = 'Warehouse Area Key';
          //  $this->aiku_id_field     = 'aiku_warehouse_area_id';
        } else {
            $this->auModel->id   = $this->auModel->data->{'Warehouse Key'};
          //  $this->aiku_id_field = 'aiku_warehouse_id';
        }
    }

    public function setModel()
    {
        $this->model = Warehouse::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateWarehouse::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreWarehouse::run($this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Warehouse Dimension')->where('Warehouse Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
