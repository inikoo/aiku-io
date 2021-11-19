<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 02:23:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Sales\ShippingSchema\StoreShippingSchema;
use App\Actions\Sales\ShippingSchema\UpdateShippingSchema;
use App\Models\Sales\ShippingSchema;
use App\Models\Trade\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateShippingSchema extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Shipping Zone Schema Dimension';
        $this->auModel->id_field = 'Shipping Zone Schema Key';
    }

    public function getParent(): Shop|null
    {
        return (new Shop())->firstWhere('aurora_id', $this->auModel->data->{'Shipping Zone Schema Store Key'});
    }

    public function parseModelData()
    {
        $this->modelData   = [
            'status'    => $this->auModel->data->{'Shipping Zone Schema Store State'} === 'Active',
            'type'      => Str::snake($this->auModel->data->{'Shipping Zone Schema Type'}, '-'),
            'name'      => $this->auModel->data->{'Shipping Zone Schema Label'},
            'aurora_id' => $this->auModel->data->{'Shipping Zone Schema Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Shipping Zone Schema Key'};
    }


    public function setModel()
    {
        $this->model = ShippingSchema::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateShippingSchema::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreShippingSchema::run($this->parent, $this->modelData);
    }

    protected function postMigrateActions(MigrationResult $res): MigrationResult
    {
        foreach (
            DB::connection('aurora')->table('Shipping Zone Dimension')
                ->where('Shipping Zone Shipping Zone Schema Key', $this->auModel->data->{'Shipping Zone Schema Key'})
                ->get() as $auroraShippingZone
        ) {
            MigrateShippingZone::run($auroraShippingZone);
        }

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Shipping Zone Schema Dimension')->where('Shipping Zone Schema Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




