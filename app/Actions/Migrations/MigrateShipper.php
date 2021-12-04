<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 04 Dec 2021 22:13:23 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Delivery\Shipper\StoreShipper;
use App\Actions\Delivery\Shipper\UpdateShipper;
use App\Models\Delivery\Shipper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateShipper extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Shipper Dimension';
        $this->auModel->id_field = 'Shipper Key';
    }

    public function parseModelData()
    {
        $this->modelData['contact'] = $this->sanitizeData(
            [
                'website' => $this->auModel->data->{'Shipper Website'},
                'company' => $this->auModel->data->{'Shipper Fiscal Name'},
                'name'    => $this->auModel->data->{'Shipper Name'},
                'phone'   => $this->auModel->data->{'Shipper Telephone'},

            ]
        );

        $this->modelData['shipper'] = $this->sanitizeData(
            [
                'code'         => Str::snake(strtolower($this->auModel->data->{'Shipper Code'}), '-'),
                'name'         => $this->auModel->data->{'Shipper Name'},
                'status'       => $this->auModel->data->{'Shipper Active'} === 'Yes',
                'tracking_url' => $this->auModel->data->{'Shipper Tracking URL'},
                'aurora_id'    => $this->auModel->data->{'Shipper Key'},


            ]
        );
        $this->auModel->id          = $this->auModel->data->{'Shipper Key'};
    }


    public function setModel()
    {
        $this->model = Shipper::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateShipper::run(shipper: $this->model, modelData: $this->modelData['shipper'], contactData: $this->modelData['contact']);
    }

    public function storeModel(): MigrationResult
    {
        return StoreShipper::run(data: $this->modelData['shipper'], contactData: $this->modelData['contact']);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Shipper Dimension')->where('Shipper Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}
