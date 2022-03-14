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
use App\Actions\Migrations\Traits\GetLocation;
use App\Models\Account\Tenant;
use App\Models\Inventory\Stock;
use App\Models\Marketing\TradeUnit;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateStock extends MigrateModel
{

    use GetLocation;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Part Dimension';
        $this->auModel->id_field = 'Part SKU';
    }

    public function getParent(): Tenant
    {
        return App('currentTenant');
    }

    public function parseModelData()
    {
        $this->modelData = $this->sanitizeData(
            [
                'description' => $this->auModel->data->{'Part Recommended Product Unit Name'},
                'code'        => strtolower($this->auModel->data->{'Part Reference'}),
                'aurora_id'   => $this->auModel->data->{'Part SKU'},
                'created_at'  => $this->auModel->data->{'Part Valid From'} ?? null,
                'state'       => match ($this->auModel->data->{'Part Status'}) {
                    'In Use' => 'active',
                    'Discontinuing' => 'discontinuing',
                    'In Process' => 'in-process',
                    'Not In Use' => 'discontinued'
                }
            ]
        );

        $this->auModel->id = $this->auModel->data->{'Part SKU'};
    }

    public function setModel()
    {
        $this->model = Stock::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {

        return UpdateStock::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreStock::run(owner: $this->parent, modelData: $this->modelData);
    }

    public function postMigrateActions(ActionResult $res): ActionResult
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

            if ($auroraPartLocationData->{'Minimum Quantity'}) {
                $settings['min_stock'] = $auroraPartLocationData->{'Minimum Quantity'};
            }
            if ($auroraPartLocationData->{'Maximum Quantity'}) {
                $settings['max_stock'] = $auroraPartLocationData->{'Maximum Quantity'};
            }
            if ($auroraPartLocationData->{'Moving Quantity'}) {
                $settings['max_stock'] = $auroraPartLocationData->{'Moving Quantity'};
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

            $location=$this->getLocation($auroraPartLocationData->{'Location Key'});
            if ($location) {
                $locationsData[$location->id] = $locationStockData;
            }
        }

        $stock->locations()->sync($locationsData);


        return $res;
    }

    protected function migrateImages()
    {
        /** @var Stock $model */
        $model = $this->model;


        $auroraImagesCollection          = $this->getModelImagesCollection('Part', $model->aurora_id);
        $auroraImagesCollectionWithImage = $auroraImagesCollection->each(function ($auroraImage) {
            if ($rawImage = MigrateRawImage::run($auroraImage)) {
                return $auroraImage->communal_image_id = $rawImage->communalImage->id;
            } else {
                return $auroraImage->communal_image_id = null;
            }
        });

        if ($auroraImagesCollectionWithImage->count()) {
            MigrateImages::run($model, $auroraImagesCollectionWithImage);
        }
    }

    protected function migrateAttachments()
    {
        /** @var Stock $model */
        $model = $this->model;

        $auroraAttachmentsCollection               = $this->getModelAttachmentsCollection('Part', $model->aurora_id);
        $auroraAttachmentsCollectionWithAttachment = $auroraAttachmentsCollection->each(function ($auroraAttachment) {
            if ($attachment = MigrateCommonAttachment::run($auroraAttachment)) {
                return $auroraAttachment->common_attachment_id = $attachment->id;
            } else {
                return $auroraAttachment->common_attachment_id = null;
            }
        });

        MigrateAttachments::run($model, $auroraAttachmentsCollectionWithAttachment);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Part Dimension')->where('Part SKU', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
