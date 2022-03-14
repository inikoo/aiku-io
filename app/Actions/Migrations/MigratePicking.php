<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 08 Dec 2021 18:52:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Delivery\Picking\StorePicking;
use App\Actions\Delivery\Picking\UpdatePicking;
use App\Actions\Migrations\Traits\GetStock;
use App\Actions\Migrations\Traits\GetStockMovement;
use App\Actions\Migrations\Traits\GetWorkSubject;
use App\Actions\Migrations\Traits\WithTransaction;
use App\Models\Delivery\DeliveryNote;
use App\Models\Delivery\Picking;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigratePicking extends MigrateModel
{
    use AsAction;
    use WithTransaction;
    use GetStockMovement;
    use GetStock;
    use GetWorkSubject;

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Inventory Transaction Fact';
        $this->auModel->id_field = 'Inventory Transaction Key';
        $this->aiku_id_field     = 'aiku_picking_id';
    }

    public function getParent(): DeliveryNote
    {
        return DeliveryNote::find($this->auModel->data->delivery_note_id);
    }

    public function parseModelData()
    {
        $stock = $this->getStock($this->auModel->data->{'Part SKU'});

        $picker=$this->getWorkSubject($this->auModel->data->{'Picker Key'});
        $packer=$this->getWorkSubject($this->auModel->data->{'Packer Key'});



        if (
            $this->auModel->data->{'Inventory Transaction Record Type'} == 'Info'
            and (in_array($this->auModel->data->{'Inventory Transaction Type'}, ['No Dispatched', 'FailSale', 'Order In Process']))
        ) {
            $stock_movement_id = null;
        } else {
            $stockMovement = $this->getStockMovement($this->auModel->data->{'Inventory Transaction Key'});
            if (!$stockMovement) {
                print "Migrate picking no stock movement\n";
                dd($this->auModel->data);
            }
            $stock_movement_id = $stockMovement->id;
        }


        $this->modelData   = [
            'stock_id'          => $stock->id,
            'required'          => round($this->auModel->data->{'Required'}, 3),
            'picked'            => round($this->auModel->data->{'Picked'}, 3),
            'picker_id'         => $picker?->id,
            'packer_id'         => $packer?->id,
            'stock_movement_id' => $stock_movement_id,
            'aurora_id'         => $this->auModel->data->{'Inventory Transaction Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Inventory Transaction Key'};
    }


    public function setModel()
    {
        $this->model = Picking::find($this->auModel->data->aiku_picking_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdatePicking::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StorePicking::run($this->parent, $this->modelData);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Inventory Transaction Fact')->where('Inventory Transaction Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




