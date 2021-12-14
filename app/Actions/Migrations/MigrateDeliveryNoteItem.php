<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 21:43:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Delivery\DeliveryNoteItem\StoreDeliveryNoteItem;
use App\Actions\Delivery\DeliveryNoteItem\UpdateDeliveryNoteItem;
use App\Models\Delivery\DeliveryNoteItem;
use App\Models\Sales\Transaction;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateDeliveryNoteItem extends MigrateModel
{
    use AsAction;
    use WithTransaction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Inventory Transaction Fact';
        $this->auModel->id_field = 'Inventory Transaction Key';
        $this->aiku_id_field     = 'aiku_dn_item_id';
    }

    public function getParent(): Transaction
    {
        return Transaction::firstWhere('aurora_id', $this->auModel->data->{'Map To Order Transaction Fact Key'});
    }

    public function parseModelData()
    {

        $auroraOTF=DB::connection('aurora')->table('Order Transaction Fact')
            ->select('Delivery Note Quantity')
            ->where('Order Transaction Fact Key', $this->auModel->data->{'Map To Order Transaction Fact Key'})
            ->first();

        $this->modelData   = [
            'delivery_note_id' => $this->auModel->data->delivery_note_id,

            'quantity'      => $auroraOTF->{'Delivery Note Quantity'},
            'created_at'    => $this->auModel->data->{'Date Created'},
            'aurora_itf_id' => $this->auModel->data->{'Inventory Transaction Key'},
            'aurora_otf_id' => $this->auModel->data->{'Map To Order Transaction Fact Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Inventory Transaction Key'};
    }


    public function setModel()
    {
        $this->model = DeliveryNoteItem::find($this->auModel->data->aiku_dn_item_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateDeliveryNoteItem::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreDeliveryNoteItem::run($this->parent, $this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Inventory Transaction Fact')->where('Inventory Transaction Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}



