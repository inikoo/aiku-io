<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 17:53:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Sales\Order\DestroyOrder;
use App\Actions\Sales\Order\StoreOrder;
use App\Actions\Sales\Order\UpdateOrder;
use App\Models\CRM\Customer;
use App\Models\Helpers\Address;
use App\Models\Sales\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;

class MigrateOrder extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Order Dimension';
        $this->auModel->id_field = 'Order Key';
        $this->aiku_id_field     = 'aiku_id';
    }

    public function getParent(): Customer
    {
        //return Shops::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Order Store Key'});
        if ($this->auModel->data->{'Order Customer Client Key'} != '') {
            $parent = Customer::withTrashed()->firstWhere('aurora_customer_client_id', $this->auModel->data->{'Order Customer Client Key'});
        } else {
            $parent = Customer::withTrashed()->firstWhere('aurora_customer_id', $this->auModel->data->{'Order Customer Key'});
        }

        if (!$parent) {
            print "Migrate order no parent";
            dd($this->auModel->data);
        }

        /*
        if (
            $parent->trashed() or
            (
                $parent->shop->type == 'fulfilment_house' and
                !$this->auModel->data->{'Order Customer Client Key'})
        ) {
            $this->ignore = true;
            DB::connection('aurora')->table($this->auModel->table)
                ->where($this->auModel->id_field, $this->auModel->data->{'Order Key'})
                ->update(['aiku_note' => 'ignore-basket']);
        }
*/

        return $parent;
    }


    public function parseModelData()
    {
        if ($this->ignore) {
            return;
        }

        $state = Str::snake($this->auModel->data->{'Order State'}, '-');

        if ($state == 'approved') {
            $state = 'in-warehouse';
        }

        $this->modelData['order'] = [
            'number'     => $this->auModel->data->{'Order Public ID'},
            'state'      => $state,
            'aurora_id'  => $this->auModel->data->{'Order Key'},
            'exchange'   => $this->auModel->data->{'Order Currency Exchange'},
            'created_at' => $this->auModel->data->{'Order Created Date'},

        ];
        $this->auModel->id        = $this->auModel->data->{'Order Key'};

        $deliveryAddressData                 = $this->parseAddress(prefix: 'Order Delivery', auAddressData: $this->auModel->data);
        $this->modelData['delivery_address'] = new Address($deliveryAddressData);

        $billingAddressData                 = $this->parseAddress(prefix: 'Order Invoice', auAddressData: $this->auModel->data);
        $this->modelData['billing_address'] = new Address($billingAddressData);
    }


    public function setModel()
    {
        $this->model = Order::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        if (!$this->ignore) {
            return UpdateOrder::run(
                order:           $this->model,
                modelData:       $this->modelData['order'],
                billingAddress:  $this->modelData['billing_address'],
                deliveryAddress: $this->modelData['delivery_address']
            );
        }

        return new ActionResult();
    }

    public function storeModel(): ActionResult
    {
        if (!$this->ignore) {
            return StoreOrder::run(
                customer:        $this->parent,
                modelData:       $this->modelData['order'],
                billingAddress:  $this->modelData['billing_address'],
                deliveryAddress: $this->modelData['delivery_address']
            );
        }

        return new ActionResult();
    }

    protected function postMigrateActions(ActionResult $res): ActionResult
    {
        if ($this->ignore) {
            $res         = new ActionResult();
            $res->status = 'error';

            return $res;
        }
        foreach (
            DB::connection('aurora')->table('Order Transaction Fact')
                ->where('Order Key', $this->auModel->data->{'Order Key'})
                ->where('Order Transaction Type', '!=', 'Refund')
                ->get() as $auroraTransaction
        ) {
            MigrateProductTransaction::run($auroraTransaction);
        }
        foreach (
            DB::connection('aurora')->table('Order No Product Transaction Fact')
                ->where('Order Key', $this->auModel->data->{'Order Key'})
                ->where('Type', '!=', 'Refund')
                ->get() as $auroraTransaction
        ) {
            MigrateNoProductTransaction::run($auroraTransaction);
        }


        return $res;
    }

    protected function migrateAttachments()
    {
        /** @var Order $order */
        $order = $this->model;

        $auroraAttachmentsCollection               = $this->getModelAttachmentsCollection('Order', $order->aurora_id);
        $auroraAttachmentsCollectionWithAttachment = $auroraAttachmentsCollection->each(function ($auroraAttachment) {
            if ($attachment = MigrateCommonAttachment::run($auroraAttachment)) {
                return $auroraAttachment->common_attachment_id = $attachment->id;
            } else {
                return $auroraAttachment->common_attachment_id = null;
            }
        });

        MigrateAttachments::run($order, $auroraAttachmentsCollectionWithAttachment);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Order Dimension')->where('Order Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




