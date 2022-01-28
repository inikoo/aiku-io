<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 12:57:24 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\CRM\FulfilmentCustomer\UpdateFulfilmentCustomer;
use App\Models\CRM\Customer;
use App\Models\CRM\FulfilmentCustomer;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Utils\ActionResult;


class MigrateFulfilmentCustomer extends MigrateModel
{

    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Customer Fulfilment Dimension';
        $this->auModel->id_field = 'Customer Fulfilment Customer Key';
    }

    public function parseModelData()
    {



        $this->modelData = $this->sanitizeData(
            [

            ]
        );



        $this->auModel->id = $this->auModel->data->{'Customer Fulfilment Customer Key'};
    }

    public function getParent(): Customer
    {
        return Customer::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Customer Fulfilment Customer Key'});
    }

    public function setModel()
    {
        $this->model = FulfilmentCustomer::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateFulfilmentCustomer::run($this->model, $this->modelData);
    }

    #[Pure] public function storeModel(): ActionResult
    {
        $res  = new ActionResult();
        $res->status ='error';
        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }


    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Customer Fulfilment Dimension')->where('Customer Fulfilment Customer Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }

        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }


}
