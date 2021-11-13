<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 12 Nov 2021 16:57:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Account\TaxBand\StoreTaxBand;
use App\Actions\Account\TaxBand\UpdateTaxBand;
use App\Models\Sales\TaxBand;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateTaxBand extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Tax Category Dimension';
        $this->auModel->id_field = 'Tax Category Key';
    }

    public function parseModelData()
    {
        $this->modelData = $this->sanitizeData(
            [
                'code'       => Str::snake(strtolower($this->auModel->data->{'Tax Category Code'}), '-'),
                'type'       => Str::snake(strtolower($this->auModel->data->{'Tax Category Type'}), '-'),
                'type_name'  => $this->auModel->data->{'Tax Category Type Name'},
                'name'       => $this->auModel->data->{'Tax Category Name'},
                'rate'       => $this->auModel->data->{'Tax Category Rate'},
                'country_id' => $this->parseCountryID($this->auModel->data->{'Tax Category Country 2 Alpha Code'} ?? null),
                'aurora_id'  => $this->auModel->data->{'Tax Category Key'},
                'status'     => $this->auModel->data->{'Tax Category Active'} == 'Yes'

            ]
        );

        $this->auModel->id = $this->auModel->data->{'Tax Category Key'};
    }


    public function setModel()
    {
        $this->model = TaxBand::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateTaxBand::run(taxBand: $this->model, modelData: $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreTaxBand::run(taxBandData: $this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Tax Category Dimension')->where('Tax Category Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}
