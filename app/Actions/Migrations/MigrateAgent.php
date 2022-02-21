<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 08 Oct 2021 18:08:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Helpers\Address\UpdateAddress;
use App\Actions\Procurement\Agent\StoreAgent;
use App\Actions\Procurement\Agent\UpdateAgent;
use App\Models\Procurement\Agent;
use App\Models\Utils\ActionResult;
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


    public function getParent()
    {
        return app('currentTenant');
    }


    public function parseSettings($settings, $auData)
    {
        data_set($settings, 'order.port_of_export', $auData->{'Agent Default Port of Export'});
        data_set($settings, 'order.port_of_import', $auData->{'Agent Default Port of Import'});
        data_set($settings, 'order.incoterm', $auData->{'Agent Default Incoterm'});
        data_set($settings, 'order.terms_and_conditions', $auData->{'Agent Default PO Terms and Conditions'});
        data_set($settings, 'order.id_format', $auData->{'Agent Order Public ID Format'});
        data_set($settings, 'products.origin', $this->parseCountryID($this->auModel->data->{'Agent Products Origin Country Code'}));

        return $settings;
    }

    public function parseMetadata($data, $auData)
    {
        data_set($data, 'order_id_counter', $auData->{'Agent Order Last Order ID'});

        return $data;
    }

    public function parseModelData()
    {
        $phone = $this->auModel->data->{'Agent Main Plain Mobile'};
        if ($phone == '') {
            $phone = $this->auModel->data->{'Agent Main Plain Telephone'};
        }


        $this->modelData['agent'] = $this->sanitizeData(
            [
                'name'         => $this->auModel->data->{'Agent Name'},
                'code'         => preg_replace('/\s/', '-', $this->auModel->data->{'Agent Code'}),
                'company_name' => $this->auModel->data->{'Agent Company Name'},
                'contact_name' => $this->auModel->data->{'Agent Main Contact Name'},
                'email'        => $this->auModel->data->{'Agent Main Plain Email'},
                'phone'        => $phone,
                'currency_id'  => $this->parseCurrencyID($this->auModel->data->{'Agent Default Currency Code'}),
                'aurora_id'    => $this->auModel->data->{'Agent Key'},
                'created_at'   => $this->auModel->data->{'Agent Valid From'}

            ]
        );

        $this->modelData['address'] = $this->parseAddress(prefix: 'Agent Contact', auAddressData: $this->auModel->data);


        $this->auModel->id = $this->auModel->data->{'Agent Key'};
    }


    public function setModel()
    {
        $this->model = Agent::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        /**  @var Agent $agent */
        $agent                                = $this->model;
        $this->modelData['agent']['data']     = $this->parseMetadata($agent->data, $this->auModel->data);
        $this->modelData['agent']['settings'] = $this->parseSettings($agent->settings, $this->auModel->data);

        $result = UpdateAgent::run(
            agent:     $agent,
            modelData: $this->modelData['agent'],
        );

        $resultAddress = UpdateAddress::run($agent->address, $this->modelData['address']);

        $result->changes = array_merge($result->changes, $resultAddress->changes);
        $result->status  = $result->changes ? 'updated' : 'unchanged';

        return $result;
    }

    public function storeModel(): ActionResult
    {
        $this->modelData['agent']['data']     = $this->parseMetadata([], $this->auModel->data);
        $this->modelData['agent']['settings'] = $this->parseSettings([], $this->auModel->data);

        return StoreAgent::run(
            parent:      $this->parent,
            data:        $this->modelData['agent'],
            addressData: $this->modelData['address']
        );
    }

    protected function migrateImages()
    {

        /**  @var Agent $agent */
        $agent                           = $this->model;
        $auroraImagesCollection          = $this->getModelImagesCollection('Agent', $agent->aurora_id);
        $auroraImagesCollectionWithImage = $auroraImagesCollection->each(function ($auroraImage) {
            if ($rawImage = MigrateRawImage::run($auroraImage,$this->auModel->data->aurora_account)) {
                return $auroraImage->communal_image_id = $rawImage->communalImage->id;
            } else {
                return $auroraImage->communal_image_id = null;
            }
        });


        MigrateImages::run($agent, $auroraImagesCollectionWithImage);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);

        if ($auroraData = DB::connection('aurora')->table('Agent Dimension')->where('Agent Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
