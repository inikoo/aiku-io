<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 20:14:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\Customer\StoreCustomer;

use App\Models\CRM\Customer;
use App\Models\Selling\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;

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

        $this->modelData['contact'] = $this->sanitizeData(
            [
                'name'                     => $this->auModel->data->{'Customer Main Contact Name'},
                'company'                  => $this->auModel->data->{'Customer Company Name'},
                'email'                    => $this->auModel->data->{'Customer Main Plain Email'},
                'phone'                    => $this->auModel->data->{'Customer Main Plain Mobile'},
                'identity_document_number' => Str::limit($this->auModel->data->{'Customer Registration Number'}),
                'website'                  => $this->auModel->data->{'Customer Website'},
                'tax_number'               => $this->auModel->data->{'Customer Tax Number'},
                'tax_number_status'        => $this->auModel->data->{'Customer Tax Number'} == ''
                    ? 'na'
                    : match ($this->auModel->data->{'Customer Tax Number Valid'}) {
                        'Yes' => 'valid',
                        'No' => 'invalid',
                        default => 'unknown'
                    },
                'created_at'               => $this->auModel->data->{'Customer First Contacted Date'}

            ]
        );

        $this->modelData['customer'] = $this->sanitizeData(
            [
                'name'               => $this->auModel->data->{'Customer Name'},
                'state'              => $state,
                'status'             => $status,
                'aurora_customer_id' => $this->auModel->data->{'Customer Key'},
                'created_at'         => $this->auModel->data->{'Customer First Contacted Date'}
            ]
        );


        $addresses = [];

        $billingAddress  = $this->parseAddress(prefix: 'Customer Invoice', auAddressData: $this->auModel->data);
        $deliveryAddress = $this->parseAddress(prefix: 'Customer Delivery', auAddressData: $this->auModel->data);

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

    public function updateModel(): MigrationResult
    {
        return $this->updateCustomer($this->auModel->data);
    }

    public function storeModel(): MigrationResult
    {
        $this->modelData['customer']['data'] = $this->parseCustomerMetadata(
            auData: $this->auModel->data,
        );
        $this->modelData['contact']['data']  = $this->parseContactMetadata(
            auData: $this->auModel->data,
        );


        return StoreCustomer::run(
            vendor:                $this->parent,
            customerData:          $this->modelData['customer'],
            contactData:           $this->modelData['contact'],
            customerAddressesData: $this->modelData['addresses']
        );

    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Customer Dimension')->where('Customer Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }

        $res  = new MigrationResult();
        $res->errors[]='Aurora model not found';
        $res->status='error';
        return $res;
    }


}
