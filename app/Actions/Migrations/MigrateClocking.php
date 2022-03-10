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
use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Models\HumanResources\Clocking;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use App\Models\HumanResources\Workplace;
use Carbon\Carbon;
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

    public function getParent(): Employee|Guest
    {
        return $this->auModel->data->subject;
    }

    public function parseModelData()
    {
        if (in_array($this->auModel->data->{'Timesheet Record Source'}, ['', 'API', 'ClockingMachine'])) {
            $type    = 'clocking-machine';
            $created_by = ClockingMachine::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Record Source Key'});
        } elseif (in_array($this->auModel->data->{'Timesheet Record Source'}, ['WorkHome', 'Break', 'WorkOutside'])) {
            $type = 'self-manual';

            $created_by = $this->parent;
        } else {
            $type    = 'manual';
            $created_by = Employee::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Authoriser Key'});
        }

        $deleted_at      = null;
        $deleted_by_type = null;
        $deleted_by_id   = null;


        if ($this->auModel->data->{'Timesheet Record Ignored'} == 'Yes') {
            if ($this->auModel->data->{'Timesheet Remove Date'}) {
                $deleted_at = $this->auModel->data->{'Timesheet Remove Date'};
            } else {
                $deleted_at = Carbon::parse($this->auModel->data->{'Timesheet Record Date'})->addHours();
            }


            $deleted_by = Employee::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Remover Key'});
            if (!$deleted_by) {
                $deleted_by = Guest::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Timesheet Remover Key'});
            }
            if ($deleted_by) {
                $deleted_by_type = class_basename($deleted_by);
                $deleted_by_id   = $deleted_by->id;
            }
        }


        $storeWorkplace = Workplace::firstWhere('type', 'hq');

        $workplaceId = $storeWorkplace->id;

        if ($type != 'clocking-machine' and $this->auModel->data->{'Timesheet Record Source'} == 'WorkHome') {
            if (class_basename($this->parent) == 'Employee') {
                /** @var Employee $employee */
                $employee = $this->parent;
                if (!$employee->homeOffice) {
                    StoreWorkplace::run($employee,
                                        [
                                            'type'        => 'home',
                                            'name'        => 'home',
                                            'timezone_id' => app('currentTenant')->timezone_id
                                        ]
                    );
                }
                $employee->refresh();

                $workplaceId = $employee->homeOffice->id;
            } else {
                /** @var Guest $guest */
                $guest = $this->parent;
                if (!$guest->ownOffice) {
                    StoreWorkplace::run($guest,
                                        [
                                            'type'        => 'home',
                                            'name'        => 'home',
                                            'timezone_id' => app('currentTenant')->timezone_id
                                        ]
                    );
                }
                $guest->refresh();

                $workplaceId = $guest->ownOffice->id;
            }
        } elseif ($type != 'clocking-machine' and $this->auModel->data->{'Timesheet Record Source'} == 'WorkOutside') {
            $workplaceId = null;
        }


        $this->modelData['created_by'] = $created_by;


        $this->modelData['clocking'] = [
            'type'            => $type,
            'clocked_at'      => $this->auModel->data->{'Timesheet Record Date'},
            'aurora_id'       => $this->auModel->data->{'Timesheet Record Key'},
            'workplace_id'    => $workplaceId,
            'notes'           => $this->auModel->data->{'Timesheet Record Note'},
            'deleted_at'      => $deleted_at,
            'deleted_by_type' => $deleted_by_type,
            'deleted_by_id'   => $deleted_by_id


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
        return StoreClocking::run(subject: $this->parent, created_by: $this->modelData['created_by'], clockingData: $this->modelData['clocking']);
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




