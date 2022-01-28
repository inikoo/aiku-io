<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 13 Oct 2021 22:48:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\CRM\CustomerClient\StoreCustomerClient;
use App\Actions\CRM\CustomerClient\UpdateCustomerClient;
use App\Actions\Helpers\Address\DeleteAddress;
use App\Actions\Helpers\Address\StoreAddress;
use App\Actions\Helpers\Address\UpdateAddress;
use App\Models\CRM\Customer;
use App\Models\CRM\CustomerClient;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;


class MigrateCustomerClient extends MigrateModel
{
    use WithCustomer;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Customer Client Dimension';
        $this->auModel->id_field = 'Customer Client Key';
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function parseContactMetadata($auData, array $metadataContact = []): array
    {
        return [];
    }

    public function parseModelData()
    {
        if ($this->auModel->data->{'Customer Client Status'} == 'Active') {
            $status         = true;
            $deactivated_at = null;
        } else {
            $status         = false;
            $metadata       = json_decode($this->auModel->data->{'Customer Client Metadata'} ?? '{}');
            $deactivated_at = $metadata->deactivated_date;
        }

        $this->modelData['contact'] = $this->sanitizeData(
            [
                'name'       => $this->auModel->data->{'Customer Client Main Contact Name'},
                'company'    => $this->auModel->data->{'Customer Client Company Name'},
                'email'      => $this->auModel->data->{'Customer Client Main Plain Email'},
                'phone'      => $this->auModel->data->{'Customer Client Main Plain Mobile'},
                'created_at' => $this->auModel->data->{'Customer Client Creation Date'},

            ]
        );

        $this->modelData['customer'] = $this->sanitizeData(
            [
                'name'                      => $this->auModel->data->{'Customer Client Code'},
                'status'                    => $status,
                'aurora_id' => $this->auModel->data->{'Customer Client Key'},
                'created_at'                => $this->auModel->data->{'Customer Client Creation Date'},
                'deactivated_at'            => $deactivated_at,
            ]
        );


        $addresses = [];

        $deliveryAddress = $this->parseAddress(prefix: 'Customer Client Contact', auAddressData: $this->auModel->data);

        $addresses['delivery'] = [
            $deliveryAddress
        ];

        $this->modelData['addresses'] = $addresses;


        $this->auModel->id = $this->auModel->data->{'Customer Client Key'};
    }

    public function getParent(): Customer
    {
        return Customer::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Customer Client Customer Key'});
    }

    public function setModel()
    {
        $this->model = CustomerClient::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        /** @var \App\Models\CRM\Customer $customerClient */
        $customerClient = $this->model;

        $result = UpdateCustomerClient::run(
            customerClient:     $customerClient,
            customerClientData: $this->modelData['customer'],
            contactData:        $this->modelData['contact'],

        );


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

            $result->model->save();
        }


        return $result;
    }

    public function storeModel(): ActionResult
    {
        return StoreCustomerClient::run(
            customer:                    $this->parent,
            customerClientData:          $this->modelData['customer'],
            contactData:                 $this->modelData['contact'],
            customerClientAddressesData: $this->modelData['addresses']
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Customer Client Dimension')->where('Customer Client Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
