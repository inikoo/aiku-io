<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 04 Jan 2022 16:48:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\HumanResources\ClockingMachine\StoreClockingMachine;
use App\Actions\HumanResources\ClockingMachine\UpdateClockingMachine;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;

class MigrateClockingMachine extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Clocking Machine Dimension';
        $this->auModel->id_field = 'Clocking Machine Key';
    }

    public function getParent(): Workplace
    {
        return Workplace::firstWhere('type','hq');
    }

    public function parseModelData()
    {
        $this->modelData   = [
            'code'      => Str::snake(strtolower($this->auModel->data->{'Clocking Machine Code'}),'-'),
            'aurora_id' => $this->auModel->data->{'Clocking Machine Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Clocking Machine Key'};
    }


    public function setModel()
    {
        $this->model = ClockingMachine::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateClockingMachine::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreClockingMachine::run($this->parent, $this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Clocking Machine Dimension')->where('Clocking Machine Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
