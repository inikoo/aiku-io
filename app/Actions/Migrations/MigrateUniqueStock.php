<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 15:21:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Inventory\UniqueStock\StoreUniqueStock;
use App\Actions\Inventory\UniqueStock\UpdateUniqueStock;
use App\Models\CRM\FulfilmentCustomer;
use App\Models\Inventory\UniqueStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;

class MigrateUniqueStock extends MigrateModel
{


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Fulfilment Asset Dimension';
        $this->auModel->id_field = 'Fulfilment Asset Key';
    }

    public function getParent(): FulfilmentCustomer
    {
        return FulfilmentCustomer::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Fulfilment Asset Customer Key'});
    }

    public function parseModelData()
    {
        $state = Str::snake($this->auModel->data->{'Fulfilment Asset State'}, '-');
        $type  = Str::snake(strtolower($this->auModel->data->{'Fulfilment Asset Type'}), '-');

        $status       = true;
        $delivered_at = null;
        if ($this->auModel->data->{'Fulfilment Asset To'}) {
            $status       = false;
            $delivered_at = $this->auModel->data->{'Fulfilment Asset To'};
        }

        $this->modelData = $this->sanitizeData(
            [
                'state'        => $state,
                'type'         => $type,
                'reference'    => $this->auModel->data->{'Fulfilment Asset Reference'},
                'notes'        => $this->auModel->data->{'Fulfilment Asset Note'},
                'aurora_id'    => $this->auModel->data->{'Fulfilment Asset Key'},
                'created_at'   => $this->auModel->data->{'Fulfilment Asset From'} ?? null,
                'delivered_at' => $delivered_at,
                'status'       => $status
            ]
        );

        $this->auModel->id = $this->auModel->data->{'Fulfilment Asset Key'};
    }

    public function setModel()
    {
        $this->model = UniqueStock::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateUniqueStock::run($this->model, $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreUniqueStock::run(owner: $this->parent, modelData: $this->modelData);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Fulfilment Asset Dimension')->where('Fulfilment Asset Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
