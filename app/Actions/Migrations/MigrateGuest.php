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
use App\Models\HumanResources\Guest;
use App\Models\Utils\ActionResult;
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
        $status = false;

        if ($auroraData = DB::connection('aurora')->table('User Dimension')->whereIn('User Type', ['Staff','Contractor'])->where('User Parent Key', $this->auModel->data->{'Staff Key'})->first()) {

            if ($auroraData->{'User Active'} == 'Yes') {
                $status = true;
            }
        }

        $data = [];
        if ($this->auModel->data->{'Staff Address'}) {
            $data['address'] = $this->auModel->data->{'Staff Address'};
        }


        if ($this->getDate($this->auModel->data->{'Staff Valid From'}) == '') {
            $data['errors'] = [
                'missing' => ['created_at']
            ];
        }

        ksort($data);




        $this->modelData['guest'] = $this->sanitizeData(
            [
                'nickname'   => strtolower($this->auModel->data->{'Staff Alias'}),

                'name'                     => $this->auModel->data->{'Staff Name'},
                'email'                    => $this->auModel->data->{'Staff Email'},
                'phone'                    => $this->auModel->data->{'Staff Telephone'},
                'identity_document_number' => $this->auModel->data->{'Staff Official ID'},
                'date_of_birth'            => $this->auModel->data->{'Staff Birthday'},

                'status'     => $status,
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

    public function updateModel(): ActionResult
    {
        return UpdateGuest::run($this->model, $this->modelData['guest']);
    }

    public function storeModel(): ActionResult
    {
        return StoreGuest::run( $this->modelData['guest']);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Staff Dimension')->where('Staff Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
