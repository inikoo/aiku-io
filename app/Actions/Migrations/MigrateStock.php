<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 12:33:18 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Inventory\Stock\StoreStock;
use App\Actions\Inventory\Stock\UpdateStock;
use App\Models\Inventory\Location;
use App\Models\Inventory\Stock;
use App\Models\Trade\TradeUnit;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateStock extends MigrateModel
{


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Part Dimension';
        $this->auModel->id_field = 'Part SKU';
    }


    public function parseModelData()
    {
        $this->modelData = $this->sanitizeData(
            [
                'description' => $this->auModel->data->{'Part Recommended Product Unit Name'},
                'code'        => strtolower($this->auModel->data->{'Part Reference'}),
                'aurora_id'   => $this->auModel->data->{'Part SKU'},
                'created_at'  => $this->getDate($this->auModel->data->{'Part Valid From'} ?? null),
            ]
        );

        $this->auModel->id = $this->auModel->data->{'Part SKU'};
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

    public function postMigrateActions(MigrationResult $res): MigrationResult
    {
        $tradeResult = MigrateTradeUnit::run($this->auModel->data);

        $res->changes = array_merge($res->changes, $tradeResult->changes);


        if ($res->status == 'unchanged') {
            $res->status = $res->changes ? 'updated' : 'unchanged';
        }
        $tradeUnit = TradeUnit::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Part SKU'});

        /** @var Stock $stock */
        $stock = $this->model;

        $stock->tradeUnits()->sync([
                                       $tradeUnit->id => [
                                           'quantity' => $this->auModel->data->{'Part Units Per Package'}
                                       ]
                                   ]);


        $locationsData = [];
        foreach (DB::connection('aurora')->table('Part Location Dimension')->where('Part SKU', $stock->aurora_id)->get() as $auroraPartLocationData) {
            $settings = [


            ];

            if( $auroraPartLocationData->{'Minimum Quantity'}){
                $settings['min_stock'] =$auroraPartLocationData->{'Minimum Quantity'};
            }
            if( $auroraPartLocationData->{'Maximum Quantity'}){
                $settings['max_stock'] =$auroraPartLocationData->{'Maximum Quantity'};
            }
            if( $auroraPartLocationData->{'Moving Quantity'}){
                $settings['max_stock'] =$auroraPartLocationData->{'Moving Quantity'};
            }


            $locationStockData = $this->sanitizeData(
                [
                    'quantity'           => round($auroraPartLocationData->{'Quantity On Hand'}, 3),
                    'audited_at'         => $auroraPartLocationData->{'Part Location Last Audit'},
                    'notes'              => $auroraPartLocationData->{'Part Location Note'},
                    'data'               => [],
                    'settings'           => $settings,
                    'aurora_part_id'     => $auroraPartLocationData->{'Part SKU'},
                    'aurora_location_id' => $auroraPartLocationData->{'Location Key'}

                ]
            );

            if ($location = Location::withTrashed()->firstWhere('aurora_id', $auroraPartLocationData->{'Location Key'})) {
                $locationsData[$location->id] = $locationStockData;
            }
        }

        $stock->locations()->sync($locationsData);


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Part Dimension')->where('Part SKU', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
