<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 23:27:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Inventory\Stock\StoreStock;
use App\Actions\Inventory\Stock\UpdateStock;
use App\Models\Inventory\Stock;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class  MigrateDeletedStock extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Part Deleted Dimension';
        $this->auModel->id_field = 'Part Deleted Key';
    }

    public function parseModelData()
    {
        $auroraDeletedData = json_decode(gzuncompress($this->auModel->data->{'Part Deleted Metadata'}));

        //print_r($auroraDeletedData);

        if ($this->auModel->data->{'Part Deleted Reference'} == '') {
            $code = 'unknown';
        } else {
            $code = strtolower($this->auModel->data->{'Part Deleted Reference'});
        }

        $this->modelData = $this->sanitizeData(
            [
                'description' => $auroraDeletedData->{'Part Recommended Product Unit Name'} ?? null,
                'code'        => $code,
                'aurora_id'   => $this->auModel->data->{'Part Deleted Key'},
                'deleted_at'  => $this->auModel->data->{'Part Deleted Date'},

                'created_at' => $auroraDeletedData->{'Part Valid From'} ?? null,
            ]
        );
        if (!$this->modelData['deleted_at']) {
            dd($this->auModel->data);
        }


        $this->auModel->id = $this->auModel->data->{'Part Deleted Key'};
    }


    public function setModel()
    {
        $this->model = Stock::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateStock::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreStock::run($this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Part Delete Dimension')->where('Part Delete Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
