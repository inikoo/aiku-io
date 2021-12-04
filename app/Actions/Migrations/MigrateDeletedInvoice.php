<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 02 Dec 2021 14:59:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Accounting\Invoice\StoreInvoice;
use App\Actions\Accounting\Invoice\UpdateInvoice;
use App\Actions\Accounting\InvoiceTransaction\StoreInvoiceTransaction;
use App\Models\Accounting\Invoice;
use App\Models\Sales\Order;
use App\Models\Sales\Transaction;
use App\Models\Trade\Product;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateDeletedInvoice extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Invoice Deleted Dimension';
        $this->auModel->id_field = 'Invoice Deleted Key';
        $this->aiku_id_field     = 'aiku_id';
    }

    public function getParent(): Order
    {
        return Order::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Invoice Deleted Order Key'});
    }


    public function parseModelData()
    {
        $data = json_decode($this->auModel->data->{'Invoice Deleted Metadata'});

        $deleted_at = $this->auModel->data->{'Invoice Deleted Date'};
        if ($deleted_at == '0000-00-00 00:00:00') {
            $deleted_at = $data->{'Invoice Date'};
        }

        $this->modelData['invoice'] = [
            'number'     => $this->auModel->data->{'Invoice Deleted Public ID'},
            'type'       => strtolower($this->auModel->data->{'Invoice Deleted Type'}),
            'exchange'   => $data->{'Invoice Currency Exchange'},
            'created_at' => $data->{'Invoice Date'},
            'deleted_at' => $deleted_at,
            'aurora_id'  => $this->auModel->data->{'Invoice Deleted Key'},

        ];

        $this->modelData['items'] = $data->items;

        $this->auModel->id = $this->auModel->data->{'Invoice Deleted Key'};
    }


    public function setModel()
    {
        $this->model = Invoice::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateInvoice::run(
            invoice:   $this->model,
            modelData: $this->modelData['invoice'],
        // invoiceAddress:  $this->modelData['invoice_address'],
        // deliveryAddress: $this->modelData['delivery_address']
        );
    }

    public function storeModel(): MigrationResult
    {
        return StoreInvoice::run(
            order:     $this->parent,
            modelData: $this->modelData['invoice'],
        // invoiceAddress:  $this->modelData['invoice_address'],
        // deliveryAddress: $this->modelData['delivery_address']
        );
    }


    protected function postMigrateActions(MigrationResult $res): MigrationResult
    {
        if ($res->status == 'inserted') {
            /** @var Invoice $invoice */
            $invoice = $res->model;

            foreach ($this->modelData['items'] as $item) {
                $transactionParent = $this->parent;

                if ($auroraData = DB::connection('aurora')->table('Order Transaction Fact')
                    ->where('Product ID', $item->{'product_pid'})
                    ->where('Order Transaction Type', '!=', 'Refund')
                    ->where('Order Key', $this->auModel->data->{'Invoice Deleted Order Key'})
                    ->first()) {
                    $transaction = Transaction::firstWhere('aurora_id', $auroraData->{'Order Transaction Fact Key'});
                    if ($transaction) {
                        $transactionParent = $transaction;
                    }
                }


                $product = (new Product())->firstWhere('aurora_product_id', $item->product_pid);


                $itemData = [
                    'invoice_id' => $res->model_id,
                    'order_id'   => $invoice->order_id,

                    'item_type'   => 'Product',
                    'item_id'     => $product->id,
                    'tax_band_id' => null,
                    'quantity'    => preg_replace('/[^0-9.]/', '', strip_tags($item->quantity)),
                    'net'         => preg_replace('/[^0-9.]/', '', $item->net),
                    'deleted_at'  => $invoice->deleted_at


                ];

                //print_r($itemData);


                StoreInvoiceTransaction::run($transactionParent, $itemData);
            }
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
        if ($auroraData = DB::connection('aurora')->table('Invoice Dimension')->where('Invoice Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




