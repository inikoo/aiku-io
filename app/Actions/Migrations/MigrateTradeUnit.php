<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 15:39:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Trade\TradeUnit\StoreTradeUnit;
use App\Actions\Trade\TradeUnit\UpdateTradeUnit;
use App\Models\Trade\TradeUnit;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateTradeUnit extends MigrateModel
{


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Part Dimension';
        $this->auModel->id_field = 'Part SKU';
        $this->aiku_id_field='aiku_unit_id';
    }


    public function parseModelData()
    {
        $this->modelData = $this->sanitizeData(
            [
                'name' => $this->auModel->data->{'Part Recommended Product Unit Name'},
                'code'        => strtolower($this->auModel->data->{'Part Reference'}),
                'aurora_id'   => $this->auModel->data->{'Part SKU'},
            ]
        );

        $this->auModel->id = $this->auModel->data->{'Part SKU'};
    }



    public function setModel()
    {
        $this->model = TradeUnit::withTrashed()->find($this->auModel->data->aiku_unit_id);


    }

    public function updateModel(): ActionResult
    {
        return UpdateTradeUnit::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreTradeUnit::run($this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Part Dimension')->where('Part SKU', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
