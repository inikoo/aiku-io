<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 06 Oct 2021 20:45:11 Malaysia Time, Kuala Lumpur, Malaysia
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

class MigrateDeletedEmployee extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Staff Deleted Dimension';
        $this->auModel->id_field = 'Staff Deleted Key';
    }

    public function parseModelData()
    {



        $auDeletedModel = json_decode(gzuncompress($this->auModel->data->{'Staff Deleted Metadata'}));



        $this->modelData['contact']  = $this->sanitizeData(
            [
                'name'                     => $auDeletedModel->data->{'Staff Name'},
                'email'                    => $auDeletedModel->data->{'Staff Email'},
                'phone'                    => $auDeletedModel->data->{'Staff Telephone'},
                'identity_document_number' => $auDeletedModel->data->{'Staff Official ID'},
                'date_of_birth'            => $auDeletedModel->data->{'Staff Birthday'}
            ]
        );
        $this->modelData['employee'] = $this->sanitizeData(
            [
                'nickname'            => strtolower($auDeletedModel->data->{'Staff Alias'}),
                'worker_number'       => $auDeletedModel->data->{'Staff ID'},
                'employment_start_at' => $this->getDate($auDeletedModel->data->{'Staff Valid From'}),
                'employment_end_at'   => $this->getDate($auDeletedModel->data->{'Staff Valid To'}),
                'type'                => Str::snake($auDeletedModel->data->{'Staff Type'}, '-'),
                'aurora_id'           => $auDeletedModel->data->{'Staff Key'},
                'state'               => match ($auDeletedModel->data->{'Staff Currently Working'}) {
                    'No' => 'left',
                    default => 'working'
                },
                'data'                => [
                    'address' => $auDeletedModel->data->{'Staff Address'},
                ],
                'deleted_at'          => $this->auModel->data->{'Staff Deleted Date'}
            ]
        );
        $this->auModel->id           = $auDeletedModel->data->{'Staff Key'};
    }


    public function setModel()
    {

        $this->model = Employee::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        $this->model = UpdateEmployee::run($this->model, $this->modelData['contact'], $this->modelData['employee']);
    }

    public function storeModel(): ?int
    {

        $employee    = StoreEmployee::run($this->modelData['contact'], $this->modelData['employee']);
        $this->model = $employee;

        return $employee?->id;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraModelID): array
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        $this->auModel->data = DB::connection('aurora')->table('Employee Dimension')->where('Employee Key', $auroraModelID)->get();

        return $this->handle($this->auModel->data);
    }
}
