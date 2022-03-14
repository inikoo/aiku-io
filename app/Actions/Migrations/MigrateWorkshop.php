<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 14:42:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;



use App\Actions\Migrations\Traits\WithWorkshop;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateWorkshop extends MigrateModel
{
    use WithWorkshop;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Dimension';
        $this->auModel->id_field = 'Supplier Key';
        $this->aiku_id_field     = 'aiku_workshop_id';
    }




    public function parseModelData()
    {
        $deleted_at = $this->auModel->data->{'Supplier Valid To'};
        if ($this->auModel->data->{'Supplier Type'} != 'Archived') {
            $deleted_at = null;
        }


        $this->modelData['workshop'] = $this->sanitizeData(
            [
                'name' => $this->auModel->data->{'Supplier Name'},
                'code' => Str::snake(
                    preg_replace('/^aw/', 'aw ', strtolower($this->auModel->data->{'Supplier Code'}))
                    ,
                    '-'
                ),

                'aurora_id'   => $this->auModel->data->{'Supplier Key'},
                'created_at'  => $this->auModel->data->{'Supplier Valid From'},
                'deleted_at'  => $deleted_at,

            ]
        );


        $this->auModel->id = $this->auModel->data->{'Supplier Key'};
    }



    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }



    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Supplier Dimension')->where('Supplier Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
