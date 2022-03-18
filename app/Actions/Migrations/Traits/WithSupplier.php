<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:12:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;


use App\Actions\Helpers\Address\UpdateAddress;
use App\Actions\Procurement\Supplier\StoreSupplier;
use App\Actions\Procurement\Supplier\UpdateSupplier;
use App\Models\Procurement\Supplier;
use App\Models\Utils\ActionResult;

use function data_set;
use function dd;

trait WithSupplier
{
    public function updateModel(): ActionResult
    {


        /**  @var Supplier $supplier */
        $supplier                                = $this->model;
        $this->modelData['supplier']['data']     = $this->parseMetadata($supplier->data, $this->auModel->data);
        $this->modelData['supplier']['settings'] = $this->parseSettings($supplier->settings, $this->auModel->data);


        $res           = UpdateSupplier::run(
            supplier:  $supplier,
            modelData: $this->modelData['supplier'],
        );
        $addressResult = UpdateAddress::run($res->model->address, $this->modelData['address']);

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
            addressData: $this->modelData['address']
        );
    }

    public function setModel()
    {
        if (Supplier::withTrashed()->find($this->auModel->data->aiku_id)) {
            dd($this->auModel->data);
        }
        $this->model = Supplier::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function parseMetadata($data, $auData)
    {
        data_set($data, 'order_id_counter', $auData->{'Supplier Order Last Order ID'} ?? null);


        return $data;
    }

    public function parseSettings($settings, $auData)
    {
        data_set($settings, 'order.port_of_export', $auData->{'Supplier Default Port of Export'} ?? null);
        data_set($settings, 'order.port_of_import', $auData->{'Supplier Default Port of Import'} ?? null);
        data_set($settings, 'order.incoterm', $auData->{'Supplier Default Incoterm'} ?? null);
        data_set($settings, 'order.terms_and_conditions', $auData->{'Supplier Default PO Terms and Conditions'} ?? null);
        data_set($settings, 'order.id_format', $auData->{'Supplier Order Public ID Format'} ?? null);

        data_set($settings, 'products.origin', $this->parseCountryID($this->auModel->data->{'Supplier Products Origin Country Code'} ?? null));

        return $settings;
    }
}


