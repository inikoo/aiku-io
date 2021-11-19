<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 19 Nov 2021 14:17:45 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Sales\ShippingZone\StoreShippingZone;
use App\Actions\Sales\ShippingZone\UpdateShippingZone;
use App\Models\Sales\ShippingSchema;
use App\Models\Sales\ShippingZone;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateShippingZone extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Shipping Zone Dimension';
        $this->auModel->id_field = 'Shipping Zone Key';
    }

    public function getParent(): ShippingSchema|null
    {
        return (new ShippingSchema())->firstWhere('aurora_id', $this->auModel->data->{'Shipping Zone Shipping Zone Schema Key'});
    }

    public function parseModelData()
    {
        $settings = [];
        $price    = [];

        $price_data = json_decode($this->auModel->data->{'Shipping Zone Price'}, true);

        $price['method'] = match ($price_data['type'] ?? null) {
            'TBC' => 'manual',
            'Step Order Items Net Amount' => 'items-net',
            'Step Order Estimated Weight' => 'weight',
            default => 'unknown'
        };

        if (in_array($price['method'], ['items-net', 'weight'])) {
            $price['steps'] = $price_data['steps'];
        }
        $territories_data = json_decode(preg_replace('/country_code/','country',$this->auModel->data->{'Shipping Zone Territories'}), true);


        $settings = [
            'price'       => $price,
            'territories' => $territories_data,

        ];



        $this->modelData   = [
            'status'     => $this->auModel->data->{'Shipping Zone Active'} === 'Yes',
            'code'       => strtolower($this->auModel->data->{'Shipping Zone Code'}),
            'name'       => $this->auModel->data->{'Shipping Zone Name'},
            'settings'   => $settings,
            'aurora_id'  => $this->auModel->data->{'Shipping Zone Key'},
            'created_at' => $this->auModel->data->{'Shipping Zone Creation Date'},
            'rank'       => $this->auModel->data->{'Shipping Zone Position'},


        ];
        $this->auModel->id = $this->auModel->data->{'Shipping Zone Key'};
    }


    public function setModel()
    {
        $this->model = ShippingZone::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateShippingZone::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreShippingZone::run($this->parent, $this->modelData);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Shipping Zone Dimension')->where('Shipping Zone Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




