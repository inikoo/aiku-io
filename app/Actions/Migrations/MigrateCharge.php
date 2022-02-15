<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 20 Nov 2021 00:00:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Sales\Charge\StoreCharge;
use App\Actions\Sales\Charge\UpdateCharge;
use App\Models\Sales\Charge;
use App\Models\Marketing\Shop;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;


class MigrateCharge extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Charge Dimension';
        $this->auModel->id_field = 'Charge Key';
    }

    public function getParent(): Shop|null
    {
        return (new Shop())->firstWhere('aurora_id', $this->auModel->data->{'Charge Store Key'});
    }

    public function parseModelData()
    {
        $settings = [];


        $this->modelData   = [
            'type'      => strtolower($this->auModel->data->{'Charge Scope'}),
            'name'      => $this->auModel->data->{'Charge Name'},
            'settings'  => $settings,
            'aurora_id' => $this->auModel->data->{'Charge Key'},


        ];
        $this->auModel->id = $this->auModel->data->{'Charge Key'};
    }


    public function setModel()
    {
        $this->model = Charge::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateCharge::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreCharge::run($this->parent, $this->modelData);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Charge Dimension')->where('Charge Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




