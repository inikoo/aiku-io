<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 11:59:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Inventory\WarehouseArea\StoreWarehouseArea;
use App\Actions\Inventory\WarehouseArea\UpdateWarehouseArea;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\WarehouseArea;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateWarehouseArea extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Warehouse Area Dimension';
        $this->auModel->id_field = 'Warehouse Area Key';
    }

    public function parseModelData()
    {
        $this->modelData   = $this->sanitizeData(
            [
                'name'      => $this->auModel->data->{'Warehouse Area Name'} ?? 'Name not set',
                'code'      => $this->auModel->data->{'Warehouse Area Code'},
                'aurora_id' => $this->auModel->data->{'Warehouse Area Key'},
            ]
        );
        $this->auModel->id = $this->auModel->data->{'Warehouse Area Key'};
    }

    public function getParent(): Warehouse|null
    {
        return (new Warehouse())->firstWhere('aurora_id', $this->auModel->data->{'Warehouse Area Warehouse Key'});
    }

    public function setModel()
    {
        $this->model = WarehouseArea::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateWarehouseArea::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreWarehouseArea::run($this->parent, $this->modelData);

    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Warehouse Area Dimension')->where('Warehouse Area Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
