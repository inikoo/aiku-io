<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 20:14:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\StoreCustomer;
use App\Actions\CRM\UpdateCustomer;
use App\Actions\Helpers\Address\DeleteAddress;
use App\Actions\Helpers\Address\StoreAddress;
use App\Actions\Helpers\Address\UpdateAddress;
use App\Models\CRM\Customer;
use App\Models\Selling\Shop;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateCustomer
{
    use AsAction;
    use MigrateAurora;


    private function parseMetadata($data, $auroraData): array
    {
        if ($auroraData->{'Customer Tax Number'} != '') {
            if ($auroraData->{'Customer Tax Number Registered Name'}) {
                data_set($data, 'tax_number.registered_name', $auroraData->{'Customer Tax Number Registered Name'});
            }
            if ($auroraData->{'Customer Tax Number Registered Address'}) {
                data_set($data, 'tax_number.registered_address', $auroraData->{'Customer Tax Number Registered Address'});
            }
            if ($auroraData->{'Customer Tax Number Validation Source'}) {
                data_set($data, 'tax_number.validation.registered_address', strtolower($auroraData->{'Customer Tax Number Validation Source'}));
            }
            if ($auroraData->{'Customer Tax Number Validation Date'}) {
                data_set($data, 'tax_number.validation.registered_address', $auroraData->{'Customer Tax Number Validation Date'});
            }
            if ($auroraData->{'Customer Tax Number Validation Message'}) {
                data_set($data, 'tax_number.validation.message', $auroraData->{'Customer Tax Number Validation Message'});
            }
            if ($auroraData->{'Customer Tax Number Valid'} == 'API_Down') {
                data_set($data, 'tax_number.validation.last_error', 'vies_api_down');
            } else {
                Arr::forget($data, 'tax_number.validation.last_error');
            }
        }

        $marketing_can_send = [];
        $subscriptions      = [];
        if ($auroraData->{'Customer Send Newsletter'} == 'Yes') {
            $subscriptions[] = 'newsletter';
        }
        if ($auroraData->{'Customer Send Email Marketing'} == 'Yes') {
            $marketing_can_send[] = 'email_marketing';
        }
        if ($auroraData->{'Customer Send Postal Marketing'} == 'Yes') {
            $marketing_can_send[] = 'postal_marketing';
        }
        data_set($data, 'marketing.subscriptions', $subscriptions);
        data_set($data, 'marketing.can_send', $marketing_can_send);

        return $data;
    }


    private function parseAuroraData($auroraData): array
    {
        $status = 'approved';
        $state  = 'active';
        if ($auroraData->{'Customer Type by Activity'} == 'Rejected') {
            $status = 'rejected';
        } elseif ($auroraData->{'Customer Type by Activity'} == 'ToApprove') {
            $state  = 'registered';
            $status = 'pending-approval';
        } elseif ($auroraData->{'Customer Type by Activity'} == 'Losing') {
            $state = 'losing';
        } elseif ($auroraData->{'Customer Type by Activity'} == 'Lost') {
            $state = 'lost';
        }


        $customerData = [
            'name'                => $auroraData->{'Customer Name'},
            'company'             => $auroraData->{'Customer Company Name'},
            'contact_name'        => $auroraData->{'Customer Main Contact Name'},
            'website'             => $auroraData->{'Customer Website'},
            'email'               => $auroraData->{'Customer Main Plain Email'},
            'phone'               => $auroraData->{'Customer Main Plain Mobile'},
            'state'               => $state,
            'status'              => $status,
            'aurora_id'           => $auroraData->{'Customer Key'},
            'registration_number' => Str::limit($auroraData->{'Customer Registration Number'}, 20),
            'tax_number'          => $auroraData->{'Customer Tax Number'},
            'tax_number_status'   => $auroraData->{'Customer Tax Number'} == ''
                ? 'na'
                : match ($auroraData->{'Customer Tax Number Valid'}) {
                    'Yes' => 'valid',
                    'No' => 'invalid',
                    default => 'unknown'
                },

            'created_at' => $auroraData->{'Customer First Contacted Date'}
        ];

        return $this->sanitizeData($customerData);
    }


    private function parseCustomerAddressees($auroraData): array
    {
        $addresses = [];

        $billingAddress  = $this->parseAddress(prefix: 'Customer Invoice', auroraData: $auroraData);
        $deliveryAddress = $this->parseAddress(prefix: 'Customer Delivery', auroraData: $auroraData);

        $addresses['billing'] = [
            $billingAddress
        ];
        if ($billingAddress != $deliveryAddress) {
            $addresses['delivery'] = [
                $deliveryAddress
            ];
        }


        return $addresses;
    }


    public function handle($auroraData, array $deletedData = null): array
    {
        $table = 'Customer Dimension';

        $result = [
            'updated'  => 0,
            'inserted' => 0,
            'errors'   => 0
        ];

        $shop = Shop::withTrashed()->firstWhere('aurora_id', $auroraData->{'Customer Store Key'});
        if (!$shop) {
            $result['errors']++;
            return $result;
        }


        $customerData   = $this->parseAuroraData($auroraData);
        $addresseesData = $this->parseCustomerAddressees($auroraData);

        if ($deletedData) {
            $table                  = 'Customer Deleted Dimension';
            $customerData['state']  = 'deleted';
            $customerData['status'] = 'deleted';

            $customerData = array_merge($customerData, $deletedData);
        }

        if ($auroraData->aiku_id) {
            $customer = Customer::withTrashed()->find($auroraData->aiku_id);

            if ($customer) {
                $customer['data'] = $this->parseMetadata(data: $customer->data, auroraData: $auroraData);
                $customer         = UpdateCustomer::run($customer, $customerData);
                $changes          = $customer->getChanges();
                if (count($changes) > 0) {
                    $result['updated']++;
                }


                if (isset($addresseesData['billing']) and count($addresseesData['billing']) > 0) {
                    UpdateAddress::run($customer->billingAddress, $addresseesData['billing'][0]);
                }

                if (isset($addresseesData['delivery']) and count($addresseesData['delivery']) > 0) {
                    if ($customer->deliveryAddress) {
                        UpdateAddress::run($customer->deliveryAddress, $addresseesData['delivery'][0]);
                    } else {
                        $address = StoreAddress::run($addresseesData['delivery'][0]);
                        $customer->addresses()->associate(
                            [
                                $address->id => ['scope' => 'delivery']
                            ]
                        );
                        $customer->delivery_address_id = $address->id;
                        $customer->save();
                    }
                } elseif ($customer->deliveryAddress and $customer->deliveryAddress->id!= $customer->billingAddress->id) {
                    $customer->delivery_address_id = null;
                    $customer->save();
                    DeleteAddress::run($customer->deliveryAddress);

                }
            } else {
                $result['errors']++;
                DB::connection('aurora')->table($table)
                    ->where('Customer Key', $auroraData->{'Customer Key'})
                    ->update(['aiku_id' => null]);

                return $result;
            }
        } else {
            $customer['data'] = $this->parseMetadata(data: [], auroraData: $auroraData);

            $customer = StoreCustomer::run($shop, $customerData, $addresseesData);
            if (!$customer) {
                $result['errors']++;

                return $result;
            }
            DB::connection('aurora')->table($table)
                ->where('Customer Key', $auroraData->{'Customer Key'})
                ->update(['aiku_id' => $customer->id]);
            $result['inserted']++;
        }


        return $result;
    }
}
