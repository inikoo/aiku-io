<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 05 Dec 2021 15:30:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Accounting\Invoice\StoreInvoice;
use App\Actions\Accounting\Invoice\UpdateInvoice;
use App\Models\Delivery\DeliveryNote;
use App\Models\Sales\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateDeliveryNote extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Delivery Note Dimension';
        $this->auModel->id_field = 'Delivery Note Key';
        $this->aiku_id_field     = 'aiku_id';
    }

    public function getParent(): Order
    {
        return Order::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Delivery Note Order Key'});
    }

    public function parseModelData()
    {
        $this->modelData['delivery_note'] = [
            'number'     => $this->auModel->data->{'Delivery Note ID'},
            'state'      => Str::snake($this->auModel->data->{'Delivery Note State'}, '-'),
            'type'       => match ($this->auModel->data->{'Delivery Note Type'}) {
                'Replacement & Shortages', 'Replacement', 'Shortages' => 'replacement',
                default => 'order'
            },
            'created_at' => $this->auModel->data->{'Delivery Note Date Created'},


            'aurora_id' => $this->auModel->data->{'Delivery Note Key'},

        ];
        $this->auModel->id                = $this->auModel->data->{'Delivery Note Key'};
    }


    public function setModel()
    {
        $this->model = DeliveryNote::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateInvoice::run(
            delivery_note: $this->model,
            modelData:     $this->modelData['invoice'],
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
    {/*
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
*/
        return $res;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Delivery Note Dimension')->where('Delivery Not Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}



