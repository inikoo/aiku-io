<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 22:55:27 Malaysia Time, Kuala Lumpur, Malaysia
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

class MigrateNoProductInvoiceTransaction extends MigrateModel
{
    use AsAction;
    use WithTransaction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Order No Product Transaction Fact';
        $this->auModel->id_field = 'Order No Product Transaction Fact Key';
        $this->aiku_id_field     = 'aiku_invoice_id';
    }

    public function getParent(): Transaction|Order|null
    {
        if ($this->auModel->data->{'Transaction Type'} == 'Charges'
            and $this->auModel->data->{'Transaction Gross Amount'} == 0
            and $this->auModel->data->{'Transaction Invoice Net Amount'} == 0
            and $this->auModel->data->{'Transaction Invoice Tax Amount'} == 0
            and $this->auModel->data->{'Transaction Refund Net Amount'} == 0
            and $this->auModel->data->{'Transaction Refund Tax Amount'} == 0
        ) {
            $this->ignore = true;

            return null;
        }


        if ($this->auModel->data->{'Type'} == 'Refund') {
            if ($auroraData = DB::connection('aurora')->table('Order No Product Transaction Fact')
                ->where('Transaction Type', $this->auModel->data->{'Transaction Type'})
                ->where('Transaction Type Key', $this->auModel->data->{'Transaction Type Key'})
                ->where('Type', 'Order')
                ->where('Order Key', $this->auModel->data->{'Order Key'})
                ->first()) {
                $transaction = Transaction::firstWhere('aurora_no_product_id', $auroraData->{'Order No Product Transaction Fact Key'});
                if ($transaction) {
                    return $transaction;
                }
            }
        } else {
            $transaction = Transaction::firstWhere('aurora_no_product_id', $this->auModel->data->{'Order No Product Transaction Fact Key'});
            if ($transaction) {
                return $transaction;
            }
        }


        return Order::withTrashed()->find($this->auModel->data->order_id);



    }

    public function parseModelData()
    {
        if ($this->ignore) {
            return;
        }
        $this->parseNoProductTransactionData();
        $this->modelData['invoice_id'] = $this->auModel->data->{'invoice_id'};
    }


    public function setModel()
    {
        $this->model = InvoiceTransaction::find($this->auModel->data->aiku_invoice_id);
    }

    public function updateModel(): ActionResult
    {
        if (!$this->ignore) {
            return UpdateInvoiceTransaction::run($this->model, $this->modelData);
        }

        return new ActionResult();
    }

    public function storeModel(): ActionResult
    {
        if (!$this->ignore) {
            return StoreInvoiceTransaction::run($this->parent, $this->modelData);
        }

        return new ActionResult();
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')
            ->table('Order No Product Transaction Fac')
            ->where('Order No Product Transaction Fact Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




