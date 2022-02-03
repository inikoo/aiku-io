<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Dec 2021 03:50:46 Malaysia Time, Kuala Lumpur, Malaysia
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
use App\Models\Utils\ActionResult;

class MigrateDeletedGuest extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Staff Deleted Dimension';
        $this->auModel->id_field = 'Staff Deleted Key';
        $this->aiku_id_field     = 'aiku_guest_id';
    }

    public function parseModelData()
    {
        $auDeletedModel = json_decode(gzuncompress($this->auModel->data->{'Staff Deleted Metadata'}));


        $this->modelData['guest'] = $this->sanitizeData(
            [
                'nickname'  => strtolower($auDeletedModel->data->{'Staff Alias'}),
                'name'  => $auDeletedModel->data->{'Staff Name'},
                'email' => $auDeletedModel->data->{'Staff Email'},
                'phone' => $auDeletedModel->data->{'Staff Telephone'},
                'aurora_id' => $auDeletedModel->data->{'Staff Key'},

                'data'       => [
                    'address' => $auDeletedModel->data->{'Staff Address'},
                ],
                'deleted_at' => $this->auModel->data->{'Staff Deleted Date'}
            ]
        );
        $this->auModel->id           = $auDeletedModel->data->{'Staff Key'};
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
        if ($auroraData = DB::connection('aurora')->table('Staff Deleted Dimension')->where('Staff Deleted Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
