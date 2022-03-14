<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 22:11:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Migrations\Traits\WithTransaction;
use App\Actions\Sales\Transaction\StoreTransaction;
use App\Actions\Sales\Transaction\UpdateTransaction;
use App\Models\Sales\Order;
use App\Models\Sales\Transaction;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateProductTransaction extends MigrateModel
{
    use AsAction;
    use WithTransaction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Order Transaction Fact';
        $this->auModel->id_field = 'Order Transaction Fact Key';
        $this->aiku_id_field     = 'aiku_id';

    }

    public function getParent(): Order
    {
        return (new Order())->firstWhere('aurora_id', $this->auModel->data->{'Order Key'});
    }

    public function parseModelData()
    {
        $this->parseProductTransactionData();

    }


    public function setModel()
    {
        $this->model = Transaction::find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateTransaction::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreTransaction::run($this->parent, $this->modelData);
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




