<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 08 Oct 2021 18:08:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Helpers\Address\UpdateAddress;
use App\Actions\Suppliers\Agent\StoreAgent;
use App\Actions\Suppliers\Agent\UpdateAgent;
use App\Models\Suppliers\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateAgent extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Agent Dimension';
        $this->auModel->id_field = 'Agent Key';
    }

    public function parseSettings($settings, $auData)
    {

        data_set($settings, 'port_of_export', $auData->{'Agent Default Port of Export'});
        data_set($settings, 'port_of_import', $auData->{'Agent Default Port of Import'});
        data_set($settings, 'incoterm', $auData->{'Agent Default Incoterm'});
        data_set($settings, 'terms_and_conditions', $auData->{'Agent Default PO Terms and Conditions'});
        data_set($settings, 'order_id_format', $auData->{'Agent Order Public ID Format'});


        return $settings;
    }

    public function parseMetadata($data, $auData)
    {
        data_set($data, 'order_id_counter', $auData->{'Agent Order Last Order ID'});


        return $data;
    }

    public function parseModelData()
    {
        $this->modelData['agent'] = $this->sanitizeData(
            [
                'name' => $this->auModel->data->{'Agent Name'},
                'code' => Str::snake(
                    preg_replace('/^aw/', 'aw ', strtolower($this->auModel->data->{'Agent Code'}))
                    ,
                    '-'
                ),

                'country_id'  => $this->parseCountryID($this->auModel->data->{'Agent Products Origin Country Code'}),
                'currency_id' => $this->parseCurrencyID($this->auModel->data->{'Agent Default Currency Code'}),
                'aurora_id'   => $this->auModel->data->{'Agent Key'},

            ]
        );

        $this->modelData['address'] = $this->parseAddress(prefix: 'Agent Contact', auAddressData: $this->auModel->data);


        $this->auModel->id = $this->auModel->data->{'Agent Key'};
    }


    public function setModel()
    {
        $this->model = Agent::find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        /**  @var Agent $agent */
        $agent                                = $this->model;
        $this->modelData['agent']['data']     = $this->parseMetadata($agent->data, $this->auModel->data);
        $this->modelData['agent']['settings'] = $this->parseSettings($agent->settings, $this->auModel->data);

        $agent = UpdateAgent::run($agent, $this->modelData['agent']);
        UpdateAddress::run($agent->address, $this->modelData['address']);

        $this->model = $agent;
    }

    public function storeModel(): ?int
    {
        $this->modelData['agent']['data']     = $this->parseMetadata([], $this->auModel->data);
        $this->modelData['agent']['settings'] = $this->parseSettings([], $this->auModel->data);

        $agent       = StoreAgent::run($this->modelData['agent'], $this->modelData['address']);
        $this->model = $agent;

        return $agent?->id;
    }

    protected function migrateImages()
    {
        /**  @var Agent $agent */
        $agent                           = $this->model;
        $auroraImagesCollection          = $this->getModelImagesCollection('Agent', $agent->aurora_id);
        $auroraImagesCollectionWithImage = $auroraImagesCollection->each(function ($auroraImage) {
            if ($image = MigrateImage::run($auroraImage)) {
                return $auroraImage->image_id = $image->id;
            } else {
                return $auroraImage->image_id = null;
            }
        });

        MigrateImageModels::run($agent, $auroraImagesCollectionWithImage);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraModelID): array
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        $auroraData = DB::connection('aurora')->table('Agent Dimension')->where('Agent Key', $auroraModelID)->get();

        return $this->handle($auroraData);
    }


}
