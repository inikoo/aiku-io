<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 03 Jan 2022 17:25:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\HumanResources\WorkTarget\StoreWorkTarget;
use App\Actions\HumanResources\WorkTarget\UpdateWorkTarget;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use App\Models\HumanResources\WorkTarget;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class MigrateWorkTarget extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Timesheet Dimension';
        $this->auModel->id_field = 'Timesheet Key';
    }


    public function getParent(): Employee|Guest
    {
        $parent = Employee::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Staff Key'});

        if (!$parent) {
            $parent = Guest::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Staff Key'});
        }
        if (!$parent) {
            print_r($this->auModel);
        }

        return $parent;
    }

    public function parseModelData()
    {
        $this->auModel->data->scheduleMarks = DB::connection('aurora')
            ->table('Timesheet Record Dimension')
            ->where('Timesheet Record Timesheet Key', $this->auModel->data->{'Timesheet Key'})
            ->whereIn('Timesheet Record Type', ['WorkingHoursMark', 'BreakMark'])
            ->orderBy('Timesheet Record Date')
            ->get();

        $resWorkSchedule = MigrateWorkSchedule::run($this->auModel->data);

        $this->modelData = [
            'date'             => $this->auModel->data->{'Timesheet Date'},
            'work_schedule_id' => $resWorkSchedule->model_id,
            'aurora_id'        => $this->auModel->data->{'Timesheet Key'},

        ];


        $this->auModel->id = $this->auModel->data->{'Timesheet Key'};
    }


    public function setModel()
    {
        $this->model = WorkTarget::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateWorkTarget::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        if ($this->parent) {
            return StoreWorkTarget::run(subject: $this->parent, workTargetData: $this->modelData);
        } else {
            $res         = new ActionResult();
            $res->status = 'error';

            return $res;
        }
    }

    public function postMigrateActions(ActionResult $res): ActionResult
    {
        /** @var WorkTarget $workTarget */
        $workTarget = $this->model;

        foreach (
            DB::connection('aurora')
                ->table('Timesheet Record Dimension')
                ->where('Timesheet Record Timesheet Key', $workTarget->aurora_id)
                ->where('Timesheet Record Type', 'ClockingRecord')
                ->get() as $auroraData
        ) {
            $auroraData->subject = $workTarget->subject;
            MigrateClocking::run($auroraData);
        }


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Timesheet Dimension')->where('Timesheet Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




