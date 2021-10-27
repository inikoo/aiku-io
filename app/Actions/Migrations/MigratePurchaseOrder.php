<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Oct 2021 22:19:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Buying\PurchaseOrder\StorePurchaseOrder;
use App\Actions\Buying\PurchaseOrder\UpdatePurchaseOrder;

use App\Models\Buying\Agent;
use App\Models\Buying\PurchaseOrder;
use App\Models\Buying\Supplier;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigratePurchaseOrder extends MigrateModel
{
    use AsAction;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Purchase Order Dimension';
        $this->auModel->id_field = 'Purchase Order Key';
    }

    public function getParent(): Supplier|Agent|null
    {

        if ($this->auModel->data->{'Purchase Order Parent'} == 'Agent') {
            return Agent::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Purchase Order Parent Key'});
        }

        return Supplier::withTrashed()->firstWhere('aurora_id', $this->auModel->data->{'Purchase Order Parent Key'});
    }

    public function parseModelData()
    {

        $date = $this->getDate($this->auModel->data->{'Purchase Order Date'});
        if (!$date) {
            $date = $this->getDate($this->auModel->data->{'Purchase Order Last Updated Date'});
        }

        $data              = [];
        $this->modelData   = [
            'number'       => $this->auModel->data->{'Purchase Order Public ID'},
            'state'        => match ($this->auModel->data->{'Purchase Order State'}) {
                'QC_Pass' => 'qc-pass',
                default => Str::snake($this->auModel->data->{'Purchase Order State'}, '-'),
            },
            'date'         => $date,
            'submitted_at' => $this->auModel->data->{'Purchase Order Submitted Date'},
            'data'         => $data,
            'aurora_id'    => $this->auModel->data->{'Purchase Order Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Purchase Order Key'};
    }


    public function setModel()
    {
        $this->model = PurchaseOrder::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): MigrationResult
    {
        return UpdatePurchaseOrder::run($this->model, $this->modelData);
    }

    public function storeModel(): MigrationResult
    {
        return StorePurchaseOrder::run($this->parent, $this->modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Purchase Order Dimension')->where('Purchase Order Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
