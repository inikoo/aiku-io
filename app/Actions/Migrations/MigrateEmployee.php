<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 13:48:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateEmployee extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Staff Dimension';
        $this->auModel->id_field = 'Staff Key';
    }

    public function parseModelData()
    {
        $this->modelData['contact'] = $this->sanitizeData(
            [
                'name'                     => $this->auModel->data->{'Staff Name'},
                'email'                    => $this->auModel->data->{'Staff Email'},
                'phone'                    => $this->auModel->data->{'Staff Telephone'},
                'identity_document_number' => $this->auModel->data->{'Staff Official ID'},
                'date_of_birth'            => $this->auModel->data->{'Staff Birthday'}
            ]
        );

        $data = [
            'address' => $this->auModel->data->{'Staff Address'},
        ];

        if ($this->getDate($this->auModel->data->{'Staff Valid From'}) == '') {
            $data['errors'] = [
                'missing' => ['created_at', 'employment_start_at']
            ];
        }

        ksort($data);

        $this->modelData['employee'] = $this->sanitizeData(
            [
                'nickname'            => strtolower($this->auModel->data->{'Staff Alias'}),
                'worker_number'       => $this->auModel->data->{'Staff ID'},
                'employment_start_at' => $this->getDate($this->auModel->data->{'Staff Valid From'}),
                'created_at'          => $this->auModel->data->{'Staff Valid From'},

                'employment_end_at' => $this->getDate($this->auModel->data->{'Staff Valid To'}),
                'type'              => Str::snake($this->auModel->data->{'Staff Type'}, '-'),
                'aurora_id'         => $this->auModel->data->{'Staff Key'},
                'state'             => match ($this->auModel->data->{'Staff Currently Working'}) {
                    'No' => 'left',
                    default => 'working'
                },
                'data'              => $data
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Staff Key'};
    }


    public function setModel()
    {
        $this->model = Employee::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateEmployee::run($this->model, $this->modelData['contact'], $this->modelData['employee']);
    }

    public function storeModel(): MigrationResult
    {
        return StoreEmployee::run($this->modelData['contact'], $this->modelData['employee']);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Staff Dimension')->where('Staff Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
