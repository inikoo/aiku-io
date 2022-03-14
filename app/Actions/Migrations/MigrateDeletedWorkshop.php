<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 14:47:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Migrations\Traits\WithSupplier;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateDeletedWorkshop extends MigrateModel
{
    use WithSupplier;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Deleted Dimension';
        $this->auModel->id_field = 'Supplier Deleted Key';
        $this->aiku_id_field     = 'aiku_workshop_id';
    }

    public function parseModelData()
    {
        $auroraDeletedData = json_decode(gzuncompress($this->auModel->data->{'Supplier Deleted Metadata'}));
        $deleted_at = $this->auModel->data->{'Supplier Deleted Date'};


        $this->modelData['supplier'] = $this->sanitizeData(
            [
                'name' => $auroraDeletedData->{'Supplier Name'},
                'code' => Str::snake(
                    preg_replace('/^aw/', 'aw ', strtolower($auroraDeletedData->{'Supplier Code'}))
                    ,
                    '-'
                ),
                'aurora_id'   => $auroraDeletedData->{'Supplier Key'},
                'created_at'  => $auroraDeletedData->{'Supplier Valid From'} ?? null,
                'deleted_at'  => $deleted_at,

            ]
        );


        $this->auModel->id = $this->auModel->data->{'Supplier Deleted Key'};
    }




    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Supplier Deleted Dimension')->where('Supplier Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}
