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
use App\Models\Trade\Product;
use App\Models\Trade\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;


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
                'shop_id'            => $this->parent->id,
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
        return $this->updateCustomer($this->auModel->data);
    }

    public function storeModel(): ActionResult
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

    public function postMigrateActions(ActionResult $res): ActionResult
    {
        $products = [];


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


            foreach (
                DB::connection('aurora')
                    ->table('Customer Favourite Product Fact')
                    ->where('Customer Favourite Product Customer Key', $this->auModel->data->{'Customer Key'})->get() as $auroraFavourites
            ) {
                if ($product = Product::withTrashed()->firstWhere('aurora_product_id', $auroraFavourites->{'Customer Favourite Product Product ID'})) {
                    $products[$product->id] =
                        [
                            'type'       => 'favourite',
                            'created_at' => $auroraFavourites->{'Customer Favourite Product Creation Date'},
                            'data'       => [],
                            'settings'   => [],
                            'aurora_id'  => $auroraFavourites->{'Customer Favourite Product Key'},

                        ];
                }
            }

            foreach (
                DB::connection('aurora')
                    ->table('Back in Stock Reminder Fact')
                    ->where('Back in Stock Reminder Customer Key', $this->auModel->data->{'Customer Key'})->get() as $auroraReminders
            ) {
                if ($product = Product::withTrashed()->firstWhere('aurora_product_id', $auroraReminders->{'Back in Stock Reminder Product ID'})) {
                    $products[$product->id] =
                        [
                            'type'       => 'notify-stock',
                            'created_at' => $auroraReminders->{'Back in Stock Reminder Creation Date'},
                            'data'       => [],
                            'settings'   => [],
                            'aurora_id'  => $auroraReminders->{'Back in Stock Reminder Key'},
                        ];
                }
            }

            foreach (
                DB::connection('aurora')
                    ->table('Customer Portfolio Fact')
                    ->where('Customer Portfolio Customer Key', $this->auModel->data->{'Customer Key'})->get() as $auroraReminders
            ) {
                if ($product = Product::withTrashed()->firstWhere('aurora_product_id', $auroraReminders->{'Customer Portfolio Product ID'})) {
                    $products[$product->id] =
                        [
                            'type'       => 'portfolio',
                            'status'     => match ($auroraReminders->{'Customer Portfolio Customers State'}) {
                                'Active' => true,
                                default => false
                            },
                            'created_at' => $auroraReminders->{'Customer Portfolio Creation Date'},
                            'deleted_at' => match ($auroraReminders->{'Customer Portfolio Customers State'}) {
                                'Removed' => $auroraReminders->{'Customer Portfolio Removed Date'},
                                default => null
                            },
                            'nickname'   => $auroraReminders->{'Customer Portfolio Reference'},
                            'data'       => [],
                            'settings'   => [],
                            'aurora_id'  => $auroraReminders->{'Customer Portfolio Key'},
                        ];
                }
            }
        }

        $customer->products()->sync($products);

        foreach ($customer->products as $customerProduct) {
            switch ($customerProduct->pivot->type) {
                case 'favourite':
                    DB::connection('aurora')->table('Customer Favourite Product Fact')
                        ->where('Customer Favourite Product Key', $customerProduct->pivot->aurora_id)
                        ->update(['aiku_id' => $customerProduct->pivot->id]);
                    break;
                case 'notify-stock':
                    DB::connection('aurora')->table('Back in Stock Reminder Fact')
                        ->where('Back in Stock Reminder Key', $customerProduct->pivot->aurora_id)
                        ->update(['aiku_id' => $customerProduct->pivot->id]);
                    break;
                case 'portfolio':
                    DB::connection('aurora')->table('Customer Portfolio Fact')
                        ->where('Customer Portfolio Key', $customerProduct->pivot->aurora_id)
                        ->update(['aiku_id' => $customerProduct->pivot->id]);
                    break;
            }
        }


        return $res;
    }

    protected function migrateAttachments()
    {
        /** @var \App\Models\CRM\Customer $customer */
        $customer = $this->model;

        $auroraAttachmentsCollection               = $this->getModelAttachmentsCollection('Customer', $customer->aurora_customer_id);
        $auroraAttachmentsCollectionWithAttachment = $auroraAttachmentsCollection->each(function ($auroraAttachment) {
            if ($attachment = MigrateCommonAttachment::run($auroraAttachment)) {
                return $auroraAttachment->common_attachment_id = $attachment->id;
            } else {
                return $auroraAttachment->common_attachment_id = null;
            }
        });

        MigrateAttachments::run($customer, $auroraAttachmentsCollectionWithAttachment);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Customer Dimension')->where('Customer Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }

        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
