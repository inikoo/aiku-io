<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 07 Oct 2021 02:30:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\UpdateCustomer;
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

        $this->modelData['customer']['data'] = $this->parseMetadata($customer->data, $auData);


        $customer = UpdateCustomer::run($customer, $this->modelData['customer']);


        if (isset($this->modelData['addresses']['billing']) and count($this->modelData['addresses']['billing']) > 0) {
            UpdateAddress::run($customer->billingAddress, $this->modelData['addresses']['billing'][0]);
        }

        if (isset($this->modelData['addresses']['delivery']) and count($this->modelData['addresses']['delivery']) > 0) {
            if ($customer->deliveryAddress) {
                UpdateAddress::run($customer->deliveryAddress, $this->modelData['addresses']['delivery'][0]);
            } else {
                $address = StoreAddress::run($this->modelData['addresses']['delivery'][0]);
                $customer->addresses()->associate(
                    [
                        $address->id => ['scope' => 'delivery']
                    ]
                );
                $customer->delivery_address_id = $address->id;
                $customer->save();
            }
        } elseif ($customer->deliveryAddress and $customer->deliveryAddress->id != $customer->billingAddress->id) {
            $customer->delivery_address_id = null;
            $customer->save();
            DeleteAddress::run($customer->deliveryAddress);
        }
        $this->model = $customer;
    }

    private function parseMetadata($data, $auData): array
    {
        if ($auData->{'Customer Tax Number'} != '') {
            if ($auData->{'Customer Tax Number Registered Name'}) {
                data_set($data, 'tax_number.registered_name', $auData->{'Customer Tax Number Registered Name'});
            }
            if ($auData->{'Customer Tax Number Registered Address'}) {
                data_set($data, 'tax_number.registered_address', $auData->{'Customer Tax Number Registered Address'});
            }
            if ($auData->{'Customer Tax Number Validation Source'}) {
                data_set($data, 'tax_number.validation.registered_address', strtolower($auData->{'Customer Tax Number Validation Source'}));
            }
            if ($auData->{'Customer Tax Number Validation Date'}) {
                data_set($data, 'tax_number.validation.registered_address', $auData->{'Customer Tax Number Validation Date'});
            }
            if ($auData->{'Customer Tax Number Validation Message'}) {
                data_set($data, 'tax_number.validation.message', $auData->{'Customer Tax Number Validation Message'});
            }
            if ($auData->{'Customer Tax Number Valid'} == 'API_Down') {
                data_set($data, 'tax_number.validation.last_error', 'vies_api_down');
            } else {
                Arr::forget($data, 'tax_number.validation.last_error');
            }
        }

        $marketing_can_send = [];
        $subscriptions      = [];
        if ($auData->{'Customer Send Newsletter'} == 'Yes') {
            $subscriptions[] = 'newsletter';
        }
        if ($auData->{'Customer Send Email Marketing'} == 'Yes') {
            $marketing_can_send[] = 'email_marketing';
        }
        if ($auData->{'Customer Send Postal Marketing'} == 'Yes') {
            $marketing_can_send[] = 'postal_marketing';
        }
        data_set($data, 'marketing.subscriptions', $subscriptions);
        data_set($data, 'marketing.can_send', $marketing_can_send);

        return $data;
    }
}


