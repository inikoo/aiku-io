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


}
