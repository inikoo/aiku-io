<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 20:14:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\StoreCustomer;

use App\Models\CRM\Customer;
use App\Models\Selling\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class MigrateDeletedCustomer extends MigrateModel
{
    use WithCustomer;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Customer Deleted Dimension';
        $this->auModel->id_field = 'Customer Key';
    }

    public function parseModelData()
    {
        $auroraDeletedData = json_decode(gzuncompress($this->auModel->data->{'Customer Deleted Metadata'}));


        $status = 'approved';
        $state  = 'active';
        $this->modelData['deleted']=$auroraDeletedData;

        $this->modelData['customer'] = $this->sanitizeData(
            [
                'name'                => $auroraDeletedData->{'Customer Name'},
                'company'             => $auroraDeletedData->{'Customer Company Name'},
                'contact_name'        => $auroraDeletedData->{'Customer Main Contact Name'},
                'website'             => $auroraDeletedData->{'Customer Website'},
                'email'               => $auroraDeletedData->{'Customer Main Plain Email'},
                'phone'               => $auroraDeletedData->{'Customer Main Plain Mobile'},
                'state'               => $state,
                'status'              => $status,
                'aurora_id'           => $auroraDeletedData->{'Customer Key'},
                'registration_number' => Str::limit($auroraDeletedData->{'Customer Registration Number'}, 20),
                'tax_number'          => $auroraDeletedData->{'Customer Tax Number'},
                'tax_number_status'   => $auroraDeletedData->{'Customer Tax Number'} == ''
                    ? 'na'
                    : match ($auroraDeletedData->{'Customer Tax Number Valid'}) {
                        'Yes' => 'valid',
                        'No' => 'invalid',
                        default => 'unknown'
                    },

                'created_at' => $auroraDeletedData->{'Customer First Contacted Date'}
            ]
        );


        $addresses = [];

        $billingAddress  = $this->parseAddress(prefix: 'Customer Invoice', auAddressData: $auroraDeletedData);
        $deliveryAddress = $this->parseAddress(prefix: 'Customer Delivery', auAddressData: $auroraDeletedData);

        $addresses['billing'] = [
            $billingAddress
        ];
        if ($billingAddress != $deliveryAddress) {
            $addresses['delivery'] = [
                $deliveryAddress
            ];
        }
        $this->modelData['addresses'] = $addresses;


        $this->auModel->id = $auroraDeletedData->{'Customer Key'};
    }

    public function getParent(): Model|Shop|Builder|\Illuminate\Database\Query\Builder|null
    {
        return Shop::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Customer Store Key'});
    }

    public function setModel()
    {
        $this->model = Customer::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel()
    {
        $this->updateCustomer($this->modelData['deleted']);
    }

    public function storeModel(): ?int
    {
        $this->modelData['customer']['data'] = $this->parseMetadata([],$this->modelData['deleted']);
        $customer                            = StoreCustomer::run($this->parent, $this->modelData['customer'], $this->modelData['addresses']);
        $this->model                         = $customer;

        return $customer?->id;
    }




    /*
        public function handle_old($auroraData, array $deletedData = null): array
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
                    } elseif ($customer->deliveryAddress and $customer->deliveryAddress->id != $customer->billingAddress->id) {
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
    */
}
