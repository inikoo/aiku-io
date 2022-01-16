<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Oct 2021 03:11:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Procurement\Supplier\StoreSupplier;
use App\Actions\Procurement\Supplier\UpdateSupplier;
use App\Actions\Helpers\Address\UpdateAddress;
use App\Models\Procurement\Supplier;
use App\Models\Utils\ActionResult;

trait WithSupplier{
    public function updateModel(): ActionResult
    {
        /**  @var Supplier $supplier */
        $supplier                                = $this->model;
        $this->modelData['supplier']['data']     = $this->parseMetadata($supplier->data, $this->auModel->data);
        $this->modelData['supplier']['settings'] = $this->parseSettings($supplier->settings, $this->auModel->data);

        $res           = UpdateSupplier::run(
            supplier:    $supplier,
            modelData:        $this->modelData['supplier'],
            contactData: $this->modelData['contact']
        );
        $addressResult = UpdateAddress::run($res->model->contact->address, $this->modelData['address']);

        $res->changes = array_merge($res->changes, $addressResult->changes);
        $res->status  = $res->changes ? 'updated' : 'unchanged';


        return $res;
    }

    public function storeModel(): ActionResult
    {
        $this->modelData['supplier']['data']     = $this->parseMetadata([], $this->auModel->data);
        $this->modelData['supplier']['settings'] = $this->parseSettings([], $this->auModel->data);

        return StoreSupplier::run(
            parent:      $this->parent,
            data:        $this->modelData['supplier'],
            contactData: $this->modelData['contact'],
            addressData: $this->modelData['address']
        );
    }

    public function setModel()
    {
        $this->model = Supplier::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function parseMetadata($data, $auData)
    {
        data_set($data, 'order_id_counter', $auData->{'Supplier Order Last Order ID'}??null);


        return $data;
    }

    public function parseSettings($settings, $auData)
    {
        data_set($settings, 'order.port_of_export', $auData->{'Supplier Default Port of Export'}??null);
        data_set($settings, 'order.port_of_import', $auData->{'Supplier Default Port of Import'}??null);
        data_set($settings, 'order.incoterm', $auData->{'Supplier Default Incoterm'}??null);
        data_set($settings, 'order.terms_and_conditions', $auData->{'Supplier Default PO Terms and Conditions'}??null);
        data_set($settings, 'order.id_format', $auData->{'Supplier Order Public ID Format'}??null);

        data_set($settings, 'products.origin', $this->parseCountryID($this->auModel->data->{'Supplier Products Origin Country Code'}??null));

        return $settings;
    }
}


