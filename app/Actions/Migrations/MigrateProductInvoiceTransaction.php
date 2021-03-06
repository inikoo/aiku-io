<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 22:40:19 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Financials\InvoiceTransaction\StoreInvoiceTransaction;
use App\Actions\Financials\InvoiceTransaction\UpdateInvoiceTransaction;
use App\Actions\Migrations\Traits\WithTransaction;
use App\Models\Financials\InvoiceTransaction;
use App\Models\Sales\Order;
use App\Models\Sales\Transaction;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateProductInvoiceTransaction extends MigrateModel
{
    use AsAction;
    use WithTransaction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Order Transaction Fact';
        $this->auModel->id_field = 'Order Transaction Fact Key';
        $this->aiku_id_field     = 'aiku_invoice_id';
    }

    public function getParent(): Transaction|Order
    {


        if ($this->auModel->data->{'Order Transaction Type'} == 'Refund') {
            if ($auroraData = DB::connection('aurora')->table('Order Transaction Fact')
                ->where('Product ID', $this->auModel->data->{'Product ID'})
                ->where('Order Transaction Type', '!=','Refund')
                ->where('Order Key', $this->auModel->data->{'Order Key'})

                ->first()) {
                $transaction = Transaction::firstWhere('aurora_id', $auroraData->{'Order Transaction Fact Key'});
                if ($transaction) {
                    return $transaction;
                }
            }
        } else {
            $transaction = Transaction::firstWhere('aurora_id', $this->auModel->data->{'Order Transaction Fact Key'});
            if ($transaction) {
                return $transaction;
            }
        }

        return Order::withTrashed()->find($this->auModel->data->order_id);
    }

    public function parseModelData()
    {
        $this->parseProductTransactionData();
        $this->modelData['invoice_id']=$this->auModel->data->{'invoice_id'};
        $this->modelData['order_id']=$this->auModel->data->{'order_id'};

    }

    public function setModel()
    {
        $this->model = InvoiceTransaction::find($this->auModel->data->aiku_invoice_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateInvoiceTransaction::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreInvoiceTransaction::run($this->parent, $this->modelData);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Order Transaction Fact')->where('Order Transaction Fact Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




