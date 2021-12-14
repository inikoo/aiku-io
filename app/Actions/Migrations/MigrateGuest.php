<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:12:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\System\Guest\StoreGuest;
use App\Actions\System\Guest\UpdateGuest;
use App\Models\System\Guest;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateGuest extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Staff Dimension';
        $this->auModel->id_field = 'Staff Key';
        $this->aiku_id_field     = 'aiku_guest_id';
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
                'missing' => ['created_at']
            ];
        }

        ksort($data);

        $this->modelData['guest'] = $this->sanitizeData(
            [
                'nickname'   => strtolower($this->auModel->data->{'Staff Alias'}),
                'created_at' => $this->auModel->data->{'Staff Valid From'},
                'aurora_id'  => $this->auModel->data->{'Staff Key'},
                'data'       => $data
            ]
        );


        $this->auModel->id = $this->auModel->data->{'Staff Key'};
    }


    public function setModel()
    {
        $this->model = Guest::withTrashed()->find($this->auModel->data->aiku_guest_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateGuest::run($this->model, $this->modelData['contact'], $this->modelData['guest']);
    }

    public function storeModel(): MigrationResult
    {
        return StoreGuest::run($this->modelData['contact'], $this->modelData['guest']);
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
