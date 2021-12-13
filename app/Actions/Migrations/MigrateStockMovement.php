<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 17:10:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Inventory\StockMovement\StoreStockMovement;
use App\Actions\Inventory\StockMovement\UpdateStockMovement;
use App\Models\Inventory\Location;
use App\Models\Inventory\Stock;
use App\Models\Inventory\StockMovement;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateStockMovement extends MigrateModel
{
    use AsAction;
    use WithTransaction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Inventory Transaction Fact';
        $this->auModel->id_field = 'Inventory Transaction Key';
    }


    public function parseModelData()
    {
        $stock    = Stock::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Part SKU'});
        $location = Location::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Location Key'});
        //enum('Found','Move','Order In Process','No Dispatched','Sale','Audit','In','Adjust','Broken','Lost','Not Found','Associate','Disassociate','Move In','Move Out','Other Out','Restock','FailSale','Production')

        if(!$stock){
            dd("Part not found: ".$this->auModel->data->{'Part SKU'});
        }

        if(!$location){
            dd("Location not found: ".$this->auModel->data->{'Location Key'});
        }


        $type = match ($this->auModel->data->{'Inventory Transaction Type'}) {
            'Move In', 'Move Out' => 'location-transfer',
            'Found' => 'found',
            'Sale' => 'delivery',
            'FailSale' => 'cancelled-restocked',
            'In' => 'purchase',
            'Broken','Lost' => 'lost',
            'Restock' => 'return',
            'Adjust','Other Out' => 'amendment',
            'Production'=>'consumption',

            default => null
        };

        if(!$type){
            dd($this->auModel->data->{'Inventory Transaction Type'});
        }

        $this->modelData = $this->sanitizeData(
            [
                'type'        => $type,
                'stock_id'    => $stock->id,
                'location_id' => $location->id,
                'quantity'    => $this->auModel->data->{'Inventory Transaction Quantity'},
                'amount'      => $this->auModel->data->{'Inventory Transaction Amount'},

                'aurora_id'  => $this->auModel->data->{'Inventory Transaction Key'},
                'created_at' => $this->auModel->data->{'Date'} ?? null,
            ]
        );

        $this->auModel->id = $this->auModel->data->{'Inventory Transaction Key'};
    }

    public function setModel()
    {
        $this->model = StockMovement::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateStockMovement::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreStockMovement::run($this->modelData);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Inventory Transaction Fact')->where('Inventory Transaction Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




