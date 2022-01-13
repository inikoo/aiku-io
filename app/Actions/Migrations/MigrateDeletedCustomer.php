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
use App\Models\Trade\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

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


        $status                     = 'approved';
        $state                      = 'active';
        $this->modelData['deleted'] = $auroraDeletedData;


        $taxNumber          = $this->auModel->data->{'Customer Tax Number'} ?? null;
        $registrationNumber = $this->auModel->data->{'Customer Registration Number'} ?? null;
        if ($registrationNumber) {
            $registrationNumber = Str::limit($registrationNumber);
        }
        $taxNumberValid = $this->auModel->data->{'Customer Tax Number Valid'} ?? 'unknown';


        $this->modelData['contact'] = $this->sanitizeData(
            [
                'name'                     => $this->auModel->data->{'Customer Main Contact Name'} ?? null,
                'company'                  => $this->auModel->data->{'Customer Company Name'} ?? null,
                'email'                    => $this->auModel->data->{'Customer Main Plain Email'} ?? null,
                'phone'                    => $this->auModel->data->{'Customer Main Plain Mobile'} ?? null,
                'identity_document_number' => $registrationNumber,
                'website'                  => $this->auModel->data->{'Customer Website'} ?? null,
                'tax_number'               => $taxNumber,
                'tax_number_status'        => $taxNumber
                    ? 'na'
                    : match ($taxNumberValid) {
                        'Yes' => 'valid',
                        'No' => 'invalid',
                        default => 'unknown'
                    },
                'created_at'               => $this->auModel->data->{'Customer First Contacted Date'} ?? null,

            ]
        );


        $this->modelData['customer'] = $this->sanitizeData(
            [
                'shop_id'            => $this->parent->id,
                'name'               => $auroraDeletedData->{'Customer Name'},
                'state'              => $state,
                'status'             => $status,
                'aurora_customer_id' => $auroraDeletedData->{'Customer Key'},
                'created_at'         => $auroraDeletedData->{'Customer First Contacted Date'},
                'deleted_at'         => $this->auModel->data->{'Customer Deleted Date'}

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

    public function getParent(): Shop
    {
        return Shop::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Customer Store Key'});
    }

    public function setModel()
    {
        $this->model = Customer::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return $this->updateCustomer($this->modelData['deleted']);
    }

    public function storeModel(): ActionResult
    {
        $this->modelData['customer']['data'] = $this->parseCustomerMetadata(
            auData: $this->modelData['deleted']
        );
        $this->modelData['contact']['data']  = $this->parseContactMetadata(
            auData: $this->modelData['deleted']
        );

        return StoreCustomer::run(
            vendor:                $this->parent,
            customerData:          $this->modelData['customer'],
            contactData:           $this->modelData['contact'],
            customerAddressesData: $this->modelData['addresses']
        );
    }

    public function postMigrateActions(ActionResult $res): ActionResult
    {


        /** @var Customer $customer */
        $customer = $this->model;

        if ($customer->vendor_type == 'Shops') {

            if($customer->shop->type=='fulfilment_house'){

                if($res->status=='inserted'){

                    DB::connection('aurora')->table('Customer Fulfilment Dimension')
                        ->where('Customer Fulfilment Customer Key', $this->auModel->id)
                        ->update(['aiku_id' => $customer->fulfilmentCustomer->id]);

                }

                foreach (
                    DB::connection('aurora')
                        ->table('Customer Fulfilment Dimension')
                        ->where('Customer Fulfilment Customer Key', $this->auModel->data->{'Customer Key'})->get() as $auroraFulfilmentCustomer
                ) {
                    MigrateFulfilmentCustomer::run($auroraFulfilmentCustomer);

                }


            }



        }


        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Customer Deleted Dimension')->where('Customer Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }

}
