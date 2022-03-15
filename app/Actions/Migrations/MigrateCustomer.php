<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 20:14:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\Migrations\Traits\WithCustomer;
use App\Models\CRM\Customer;
use App\Models\Marketing\Product;
use App\Models\Marketing\Shop;
use App\Models\Utils\ActionResult;
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

        $this->modelData['customer'] = $this->sanitizeData(
            [
                'shop_id'                  => $this->parent->id,
                'name'                     => $this->auModel->data->{'Customer Name'},
                'state'                    => $state,
                'status'                   => $status,
                'contact_name'             => $this->auModel->data->{'Customer Main Contact Name'},
                'company_name'             => $this->auModel->data->{'Customer Company Name'},
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
                'aurora_id'                => $this->auModel->data->{'Customer Key'},
                'created_at'               => $this->auModel->data->{'Customer First Contacted Date'}
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
        $this->modelData['customer']['data']            = $this->parseCustomerMetadata(
            auData: $this->auModel->data,
        );
        $this->modelData['customer']['tax_number_data'] = $this->parseTaxNumberMetadata(
            auData: $this->auModel->data,
        );


        return StoreCustomer::run(
            shop:                  $this->parent,
            customerData:          $this->modelData['customer'],
            customerAddressesData: $this->modelData['addresses']
        );
    }



    public function getPortFolio(): array
    {
        $products = [];

        foreach (
            DB::connection('aurora')
                ->table('Customer Portfolio Fact')
                ->where('Customer Portfolio Customer Key', $this->auModel->data->{'Customer Key'})->get() as $auroraReminders
        ) {
            $product = Product::withTrashed()->firstWhere('aurora_id', $auroraReminders->{'Customer Portfolio Product ID'});
            if ($product) {
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

        return $products;
    }

    public function postMigrateActions(ActionResult $res): ActionResult
    {
        /** @var Customer $customer */
        $customer = $this->model;


        if ($customer->shop->type == 'fulfilment_house') {
            if ($res->status == 'inserted') {
                DB::connection('aurora')->table('Customer Fulfilment Dimension')
                    ->where('Customer Fulfilment Customer Key', $this->auModel->id)
                    ->update(['aiku_id' => $customer->id]);
            }

            foreach (
                DB::connection('aurora')
                    ->table('Customer Fulfilment Dimension')
                    ->where('Customer Fulfilment Customer Key', $this->auModel->data->{'Customer Key'})->get() as $auroraFulfilmentCustomer
            ) {
                MigrateFulfilmentCustomer::run($auroraFulfilmentCustomer);
            }
        }


        MigrateFavourites::run($customer);
        MigrateReminders::run($customer);
        MigratePortfolio::run($customer);



        return $res;
    }

    protected function migrateAttachments()
    {
        /** @var \App\Models\CRM\Customer $customer */
        $customer = $this->model;

        $auroraAttachmentsCollection               = $this->getModelAttachmentsCollection('Customer', $customer->aurora_id);
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
        $auroraData = DB::connection('aurora')->table('Customer Dimension')->where('Customer Key', $auroraID)->first();
        if ($auroraData) {
            return $this->handle($auroraData);
        }

        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
