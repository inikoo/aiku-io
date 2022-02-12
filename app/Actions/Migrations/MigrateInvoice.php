<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 22:09:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Financials\Invoice\StoreInvoice;
use App\Actions\Financials\Invoice\UpdateInvoice;
use App\Models\Financials\Invoice;
use App\Models\Helpers\Address;
use App\Models\Sales\Order;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;

class MigrateInvoice extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Invoice Dimension';
        $this->auModel->id_field = 'Invoice Key';
        $this->aiku_id_field     = 'aiku_id';
    }

    public function getParent(): Order
    {
        $order = Order::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Invoice Order Key'});
        if (!$order) {
            print "Migrate invoice no parent order";
            dd($this->auModel->data);
        }

        return $order;
    }


    public function parseModelData()
    {
        $this->modelData['invoice'] = [
            'number'     => $this->auModel->data->{'Invoice Public ID'},
            'type'       => strtolower($this->auModel->data->{'Invoice Type'}),
            'created_at' => $this->auModel->data->{'Invoice Date'},
            'exchange'   => $this->auModel->data->{'Invoice Currency Exchange'},
            'aurora_id'  => $this->auModel->data->{'Invoice Key'},
        ];

        $billingAddressData                 = $this->parseAddress(prefix: 'Invoice', auAddressData: $this->auModel->data);
        $this->modelData['billing_address'] = new Address($billingAddressData);

        $this->auModel->id = $this->auModel->data->{'Invoice Key'};
    }


    public function setModel()
    {
        $this->model = Invoice::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateInvoice::run(
            invoice:        $this->model,
            modelData:      $this->modelData['invoice'],
            billingAddress: $this->modelData['billing_address'],
        );
    }

    public function storeModel(): ActionResult
    {
        return StoreInvoice::run(
            order:          $this->parent,
            modelData:      $this->modelData['invoice'],
            billingAddress: $this->modelData['billing_address'],
        );
    }


    protected function postMigrateActions(ActionResult $res): ActionResult
    {
        foreach (
            DB::connection('aurora')->table('Order Transaction Fact')
                ->where('Invoice Key', $this->auModel->data->{'Invoice Key'})
                ->get() as $auroraTransaction
        ) {
            $auroraTransaction->{'invoice_id'} = $res->model_id;
            $auroraTransaction->{'order_id'}   = $this->parent->id;
            MigrateProductInvoiceTransaction::run($auroraTransaction);
        }
        foreach (
            DB::connection('aurora')->table('Order No Product Transaction Fact')
                ->where('Invoice Key', $this->auModel->data->{'Invoice Key'})
                ->get() as $auroraTransaction
        ) {
            $auroraTransaction->{'invoice_id'} = $res->model_id;
            $auroraTransaction->{'order_id'}   = $this->parent->id;

            MigrateNoProductInvoiceTransaction::run($auroraTransaction);
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
        if ($auroraData = DB::connection('aurora')->table('Invoice Dimension')->where('Invoice Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




