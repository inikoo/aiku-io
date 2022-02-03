<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Oct 2021 02:30:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\Customer\UpdateCustomer;
use App\Actions\Helpers\Address\DeleteAddress;
use App\Actions\Helpers\Address\StoreAddress;
use App\Actions\Helpers\Address\UpdateAddress;
use Illuminate\Support\Arr;

trait WithCustomer
{
    public function updateCustomer($auData)
    {
        /** @var \App\Models\CRM\Customer $customer */
        $customer = $this->model;

        $this->modelData['customer']['data']            = $this->parseCustomerMetadata(
            auData:           $auData,
            metadataCustomer: $customer->data,
        );
        $this->modelData['customer']['tax_number_data'] = $this->parseTaxNumberMetadata(
            auData:            $auData,
            metadataTaxNumber: $customer->tax_number_data,
        );

        $result = UpdateCustomer::run(
            customer:     $customer,
            customerData: $this->modelData['customer'],

        );


        if (isset($this->modelData['addresses']['billing']) and count($this->modelData['addresses']['billing']) > 0) {
            UpdateAddress::run($result->model->billingAddress, $this->modelData['addresses']['billing'][0]);
            $result->model->billing_address_id = $result->model->billingAddress->id;
            $result->model->save();
        }

        if (isset($this->modelData['addresses']['delivery']) and count($this->modelData['addresses']['delivery']) > 0) {
            if ($result->model->deliveryAddress) {
                UpdateAddress::run($result->model->deliveryAddress, $this->modelData['addresses']['delivery'][0]);
            } else {
                $address = StoreAddress::run($this->modelData['addresses']['delivery'][0]);
                $result->model->addresses()->associate(
                    [
                        $address->id => ['scope' => 'delivery']
                    ]
                );
                $result->model->delivery_address_id = $address->id;
                $result->model->save();
            }
        } else {
            if ($result->model->deliveryAddress and $result->model->deliveryAddress->id != $result->model->billingAddress->id) {
                DeleteAddress::run($result->model->deliveryAddress);
            }

            $result->model->delivery_address_id = null;
            if ($result->model->shop->type == 'shop') {
                $result->model->delivery_address_id = $result->model->billing_address_id;
            }
            $result->model->save();
        }


        return $result;
    }

    private function parseCustomerMetadata($auData, array $metadataCustomer = []): array
    {
        $marketing_can_send = [];
        $subscriptions      = [];
        if ($auData->{'Customer Send Newsletter'} ?? null == 'Yes') {
            $subscriptions[] = 'newsletter';
        }
        if ($auData->{'Customer Send Email Marketing'} ?? null == 'Yes') {
            $marketing_can_send[] = 'email_marketing';
        }
        if ($auData->{'Customer Send Postal Marketing'} ?? null == 'Yes') {
            $marketing_can_send[] = 'postal_marketing';
        }
        data_set($metadataCustomer, 'marketing.subscriptions', $subscriptions);
        data_set($metadataCustomer, 'marketing.can_send', $marketing_can_send);

        return $metadataCustomer;
    }

    private function parseTaxNumberMetadata($auData, array $metadataTaxNumber = []): array
    {
        if ($auData->{'Customer Tax Number'} != '') {
            if ($auData->{'Customer Tax Number Registered Name'}) {
                data_set($metadataTaxNumber, 'registered_name', $auData->{'Customer Tax Number Registered Name'});
            }
            if ($auData->{'Customer Tax Number Registered Address'}) {
                data_set($metadataTaxNumber, 'registered_address', $auData->{'Customer Tax Number Registered Address'});
            }
            if ($auData->{'Customer Tax Number Validation Source'}) {
                data_set($metadataTaxNumber, 'validation.registered_address', strtolower($auData->{'Customer Tax Number Validation Source'}));
            }
            if ($auData->{'Customer Tax Number Validation Date'}) {
                data_set($metadataTaxNumber, 'validation.registered_address', $auData->{'Customer Tax Number Validation Date'});
            }
            if ($auData->{'Customer Tax Number Validation Message'}) {
                data_set($metadataTaxNumber, 'validation.message', $auData->{'Customer Tax Number Validation Message'});
            }
            if ($auData->{'Customer Tax Number Valid'} == 'API_Down') {
                data_set($metadataTaxNumber, 'validation.last_error', 'vies_api_down');
            } else {
                Arr::forget($metadataTaxNumber, 'validation.last_error');
            }
        }


        return $metadataTaxNumber;
    }
}


