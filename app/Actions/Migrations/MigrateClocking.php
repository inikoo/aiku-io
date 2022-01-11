<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 05 Jan 2022 15:23:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\HumanResources\Clocking\StoreClocking;
use App\Actions\HumanResources\Clocking\UpdateClocking;
use App\Models\HumanResources\Clocking;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;


class MigrateClocking extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Timesheet Record Dimension';
        $this->auModel->id_field = 'Timesheet Record Key';
    }

    public function getParent(): Employee
    {
        return Employee::withTrashed()->find($this->auModel->data->employee_id);
    }

    public function parseModelData()
    {

        if (in_array($this->auModel->data->{'Timesheet Record Source'}, ['', 'API', 'ClockingMachine'])) {
            $source    = 'clocking-machine';
            $creator = ClockingMachine::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Record Source Key'});
        } elseif (in_array($this->auModel->data->{'Timesheet Record Source'}, ['WorkHome', 'Break', 'WorkOutside'])) {
            $source = 'self-manual';

            $creator = $this->parent;
        } else {
            $source    = 'manual';
            $creator = Employee::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Authoriser Key'});
        }

        //if (!$creator) {
           // print "creator not found";
           // print_r($this->auModel->data);
        //}


        $this->modelData['creator'] = $creator;


        $this->modelData['clocking'] = [
            'source'       => $source,
            'clocked_at' => $this->auModel->data->{'Timesheet Record Date'},
            'aurora_id'  => $this->auModel->data->{'Timesheet Record Key'},


        ];
        $this->auModel->id           = $this->auModel->data->{'Timesheet Record Key'};
    }


    public function setModel()
    {
        $this->model = Clocking::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateClocking::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreClocking::run(clockable: $this->parent, creator: $this->modelData['creator'], clockingData: $this->modelData['clocking']);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Timesheet Record Dimension')->where('Timesheet Record Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




