<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 17 Nov 2021 15:02:59 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Sales\BasketTransaction\StoreBasketTransaction;
use App\Actions\Sales\BasketTransaction\UpdateBasketTransaction;
use App\Models\Sales\Basket;
use App\Models\Sales\BasketTransaction;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateBasketProductTransaction extends MigrateModel
{
    use AsAction;
    use WithTransaction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Order Transaction Fact';
        $this->auModel->id_field = 'Order Transaction Fact Key';
        $this->aiku_id_field     = 'aiku_basket_id';
    }

    public function getParent(): Basket
    {
        return (new Basket())->firstWhere('aurora_id', $this->auModel->data->{'Order Key'});
    }

    public function parseModelData()
    {
        $this->parseProductTransactionData();
    }


    public function setModel()
    {
        $this->model = BasketTransaction::find($this->auModel->data->aiku_basket_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdateBasketTransaction::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StoreBasketTransaction::run($this->parent, $this->modelData);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Order Transaction Fact')->where('Order Transaction Fact Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




