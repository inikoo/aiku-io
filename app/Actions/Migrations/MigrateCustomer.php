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

class MigrateCustomer extends MigrateModel
{
use WithCustomer;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Customer Dimension';
        $this->auModel->id_field = 'Customer Key';
    }

    public function parseModelData()
    {
        $status = 'approved';
        $state  = 'active';
        if ($this->auModel->data->{'Customer Type by Activity'} == 'Rejected') {
            $status = 'rejected';
        } elseif ($this->auModel->data->{'Customer Type by Activity'} == 'ToApprove') {
            $state  = 'registered';
            $status = 'pending-approval';
        } elseif ($this->auModel->data->{'Customer Type by Activity'} == 'Losing') {
            $state = 'losing';
        } elseif ($this->auModel->data->{'Customer Type by Activity'} == 'Lost') {
            $state = 'lost';
        }


        $this->modelData['customer'] = $this->sanitizeData(
            [
                'name'                => $this->auModel->data->{'Customer Name'},
                'company'             => $this->auModel->data->{'Customer Company Name'},
                'contact_name'        => $this->auModel->data->{'Customer Main Contact Name'},
                'website'             => $this->auModel->data->{'Customer Website'},
                'email'               => $this->auModel->data->{'Customer Main Plain Email'},
                'phone'               => $this->auModel->data->{'Customer Main Plain Mobile'},
                'state'               => $state,
                'status'              => $status,
                'aurora_id'           => $this->auModel->data->{'Customer Key'},
                'registration_number' => Str::limit($this->auModel->data->{'Customer Registration Number'}, 20),
                'tax_number'          => $this->auModel->data->{'Customer Tax Number'},
                'tax_number_status'   => $this->auModel->data->{'Customer Tax Number'} == ''
                    ? 'na'
                    : match ($this->auModel->data->{'Customer Tax Number Valid'}) {
                        'Yes' => 'valid',
                        'No' => 'invalid',
                        default => 'unknown'
                    },

                'created_at' => $this->auModel->data->{'Customer First Contacted Date'}
            ]
        );


        $addresses = [];

        $billingAddress  = $this->parseAddress(prefix: 'Customer Invoice',auAddressData:$this->auModel->data);
        $deliveryAddress = $this->parseAddress(prefix: 'Customer Delivery',auAddressData:$this->auModel->data);

        $addresses['billing'] = [
            $billingAddress
        ];
        if ($billingAddress != $deliveryAddress) {
            $addresses['delivery'] = [
                $deliveryAddress
            ];
        }
        $this->modelData['addresses'] = $addresses;


        $this->auModel->id = $this->auModel->data->{'Customer Key'};
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
        $this->updateCustomer($this->auModel->data);
    }

    public function storeModel(): ?int
    {
        $this->modelData['customer']['data'] = $this->parseMetadata([],$this->auModel->data);
        $customer    = StoreCustomer::run($this->parent, $this->modelData['customer'], $this->modelData['addresses']);
        $this->model = $customer;

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
