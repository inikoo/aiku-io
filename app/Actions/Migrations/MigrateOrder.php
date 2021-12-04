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
use App\Models\Trade\Shop;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

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

    public function getParent(): Shop
    {
        return Shop::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Order Store Key'});
    }


    public function parseModelData()
    {
        $customer = Customer::withTrashed()->firstWhere('aurora_customer_id', $this->auModel->data->{'Order Customer Key'});
        if ($this->auModel->data->{'Order Customer Client Key'} != '') {
            $customerClient     = Customer::withTrashed()->firstWhere('aurora_customer_client_id', $this->auModel->data->{'Order Customer Client Key'});
            $customer_client_id = $customerClient->id;
        }

        $state = 'dispatched';
        if ($this->auModel->data->{'Order State'} == 'Approved') {
            $state = 'in-warehouse';
        }

        $this->modelData['order'] = [
            'number'             => $this->auModel->data->{'Order Public ID'},
            'customer_id'        => $customer->id,
            'customer_client_id' => $customer_client_id ?? null,
            'state'              => $state,
            'aurora_id'          => $this->auModel->data->{'Order Key'},
            'exchange'           => $this->auModel->data->{'Order Currency Exchange'}

        ];
        $this->auModel->id        = $this->auModel->data->{'Order Key'};

        $deliveryAddressData                 = $this->parseAddress(prefix: 'Order Delivery', auAddressData: $this->auModel->data);
        $this->modelData['delivery_address'] = new Address($deliveryAddressData);

        $invoiceAddressData                 = $this->parseAddress(prefix: 'Order Invoice', auAddressData: $this->auModel->data);
        $this->modelData['invoice_address'] = new Address($invoiceAddressData);
    }


    public function setModel()
    {
        $this->model = Order::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        if (in_array($this->auModel->data->{'Order State'}, ['Dispatched', 'Approved']) and !$this->ignore) {
            return UpdateOrder::run(
                order:           $this->model,
                modelData:       $this->modelData['order'],
                invoiceAddress:  $this->modelData['invoice_address'],
                deliveryAddress: $this->modelData['delivery_address']
            );
        } else {
            return DestroyOrder::run($this->model);
        }
    }

    public function storeModel(): MigrationResult
    {
        if (in_array($this->auModel->data->{'Order State'}, ['Dispatched', 'Approved']) and !$this->ignore) {
            return StoreOrder::run(
                shop:            $this->parent,
                modelData:       $this->modelData['order'],
                invoiceAddress:  $this->modelData['invoice_address'],
                deliveryAddress: $this->modelData['delivery_address']
            );
        }

        return new MigrationResult();
    }

    protected function postMigrateActions(MigrationResult $res): MigrationResult
    {
        if ($this->ignore) {
            $res         = new MigrationResult();
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

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Order Dimension')->where('Order Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




