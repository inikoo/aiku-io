<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 14:21:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Helpers\Address\UpdateAddress;

use App\Actions\Suppliers\Supplier\StoreSupplier;
use App\Actions\Suppliers\Supplier\UpdateSupplier;
use App\Models\Suppliers\Agent;
use App\Models\Suppliers\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

class MigrateSupplier extends MigrateModel
{
    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Dimension';
        $this->auModel->id_field = 'Supplier Key';
    }


    public function getParent()
    {
        if ($this->auModel->data->{'Supplier Has Agent'} == 'Yes') {
            $res = DB::connection('aurora')->table('Agent Supplier Bridge')
                ->leftJoin('Agent Dimension', 'Agent Supplier Agent Key', '=', 'Agent Key')
                ->where('Agent Supplier Supplier Key', $this->auModel->data->{'Supplier Key'})
                ->select('aiku_id')
                ->first();


            return Agent::findOrFail($res->aiku_id);
        } else {
            return app('currentTenant');
        }
    }


    public function parseSettings($settings, $auData)
    {
        data_set($settings, 'order.port_of_export', $auData->{'Supplier Default Port of Export'});
        data_set($settings, 'order.port_of_import', $auData->{'Supplier Default Port of Import'});
        data_set($settings, 'order.incoterm', $auData->{'Supplier Default Incoterm'});
        data_set($settings, 'order.terms_and_conditions', $auData->{'Supplier Default PO Terms and Conditions'});
        data_set($settings, 'order.id_format', $auData->{'Supplier Order Public ID Format'});

        data_set($settings, 'products.origin', $this->parseCountryID($this->auModel->data->{'Supplier Products Origin Country Code'}));

        return $settings;
    }

    public function parseMetadata($data, $auData)
    {
        data_set($data, 'order_id_counter', $auData->{'Supplier Order Last Order ID'});


        return $data;
    }

    public function parseModelData()
    {
        $deleted_at = $this->auModel->data->{'Supplier Valid To'};
        if ($this->auModel->data->{'Supplier Type'} != 'Archived') {
            $deleted_at = null;
        }
        $phone = $this->auModel->data->{'Supplier Main Plain Mobile'};
        if ($phone == '') {
            $phone = $this->auModel->data->{'Supplier Main Plain Telephone'};
        }

        //print_r($this->auModel->data);

        $this->modelData['contact'] = $this->sanitizeData(
            [
                'company' => $this->auModel->data->{'Supplier Company Name'},
                'name'    => $this->auModel->data->{'Supplier Main Contact Name'},
                'email'   => $this->auModel->data->{'Supplier Main Plain Email'},
                'phone'   => $phone,

            ]
        );

        $this->modelData['supplier'] = $this->sanitizeData(
            [
                'name' => $this->auModel->data->{'Supplier Name'},
                'code' => Str::snake(
                    preg_replace('/^aw/', 'aw ', strtolower($this->auModel->data->{'Supplier Code'}))
                    ,
                    '-'
                ),

                'currency_id' => $this->parseCurrencyID($this->auModel->data->{'Supplier Default Currency Code'}),
                'aurora_id'   => $this->auModel->data->{'Supplier Key'},
                'deleted_at'  => $deleted_at

            ]
        );

        $this->modelData['address'] = $this->parseAddress(prefix: 'Supplier Contact', auAddressData: $this->auModel->data);


        $this->auModel->id = $this->auModel->data->{'Supplier Key'};
    }


    public function setModel()
    {
        $this->model = Supplier::find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        /**  @var Supplier $supplier */
        $supplier                                = $this->model;
        $this->modelData['supplier']['data']     = $this->parseMetadata($supplier->data, $this->auModel->data);
        $this->modelData['supplier']['settings'] = $this->parseSettings($supplier->settings, $this->auModel->data);

        $supplier = UpdateSupplier::run(
            supplier:    $supplier,
            data:        $this->modelData['supplier'],
            contactData: $this->modelData['contact']
        );
        UpdateAddress::run($supplier->contact->address, $this->modelData['address']);

        $this->model = $supplier;
    }

    public function storeModel(): ?int
    {
        $this->modelData['supplier']['data']     = $this->parseMetadata([], $this->auModel->data);
        $this->modelData['supplier']['settings'] = $this->parseSettings([], $this->auModel->data);

        $supplier    = StoreSupplier::run(
            parent: $this->parent,
            data:   $this->modelData['supplier'],
            contactData: $this->modelData['contact'],
            addressData: $this->modelData['address']
        );
        $this->model = $supplier;

        return $supplier?->id;
    }

    protected function migrateImages()
    {
        /**  @var Supplier $supplier */
        $supplier                        = $this->model;
        $auroraImagesCollection          = $this->getModelImagesCollection('Supplier', $supplier->aurora_id);
        $auroraImagesCollectionWithImage = $auroraImagesCollection->each(function ($auroraImage) {
            if ($image = MigrateImage::run($auroraImage)) {
                return $auroraImage->image_id = $image->id;
            } else {
                return $auroraImage->image_id = null;
            }
        });

        MigrateImageModels::run($supplier, $auroraImagesCollectionWithImage);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraModelID): array
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        $auroraData = DB::connection('aurora')->table('Supplier Dimension')->where('Supplier Key', $auroraModelID)->get();

        return $this->handle($auroraData);
    }


}
