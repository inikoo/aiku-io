<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 19 Mar 2022 11:08:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Helpers\Address\UpdateAddress;
use App\Actions\Marketing\Shop\StoreShop;
use App\Actions\Marketing\Shop\UpdateShop;
use App\Models\Marketing\Shop;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateAgentShop extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Agent Dimension';
        $this->auModel->id_field = 'Agent Key';
        $this->aiku_id_field     = 'agent_aiku_id';
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
                'type'         => 'agent',
                'subtype'      => 'b2b',
                'code'         => preg_replace('/\s/', '-', $this->auModel->data->{'Agent Code'}),
                'name'         => $this->auModel->data->{'Agent Name'},
                'company_name' => $this->auModel->data->{'Agent Company Name'},
                'contact_name' => $this->auModel->data->{'Agent Main Contact Name'},
                'email'        => $this->auModel->data->{'Agent Main Plain Email'},
                'phone'        => $phone,
                'language_id'  => $this->parseLanguageID('en'),
                'timezone_id'  => app('currentTenant')->timezone_id,
                'currency_id'  => $this->parseCurrencyID($this->auModel->data->{'Agent Default Currency Code'}),
                'aurora_id'    => $this->auModel->data->{'Agent Key'},
                'state'        => 'open',
                'open_at'      => $this->auModel->data->{'Agent Valid From'},
                'created_at'   => $this->auModel->data->{'Agent Valid From'}

            ]
        );



        $this->modelData['address'] = $this->parseAddress(prefix: 'Agent Contact', auAddressData: $this->auModel->data);
        $this->auModel->id          = $this->auModel->data->{'Agent Key'};
    }


    public function setModel()
    {
        $this->model = Shop::withTrashed()->find($this->auModel->data->agent_aiku_id);
    }

    public function updateModel(): ActionResult
    {
        /**  @var Shop $shop */
        $shop                                 = $this->model;
        $this->modelData['agent']['data']     = $this->parseMetadata($shop->data, $this->auModel->data);
        $this->modelData['agent']['settings'] = $this->parseSettings($shop->settings, $this->auModel->data);

        $result = UpdateShop::run(
            shop:      $shop,
            modelData: $this->modelData['agent'],
        );

        $resultAddress = UpdateAddress::run($shop->address, $this->modelData['address']);

        $result->changes = array_merge($result->changes, $resultAddress->changes);
        $result->status  = $result->changes ? 'updated' : 'unchanged';

        return $result;
    }

    public function storeModel(): ActionResult
    {
        $this->modelData['agent']['data']     = $this->parseMetadata([], $this->auModel->data);
        $this->modelData['agent']['settings'] = $this->parseSettings([], $this->auModel->data);

        return StoreShop::run(
            modelData:   $this->modelData['agent'],
            addressData: $this->modelData['address']
        );
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
