<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 17:09:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Migrations\Traits\GetSupplier;
use App\Actions\Procurement\ProcurementDelivery\StoreProcurementDelivery;
use App\Actions\Procurement\ProcurementDelivery\UpdateProcurementDelivery;
use App\Models\Procurement\Agent;
use App\Models\Procurement\ProcurementDelivery;
use App\Models\Procurement\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;

class MigrateProcurementDelivery extends MigrateModel
{
    use AsAction;
    use GetSupplier;


    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Supplier Delivery Dimension';
        $this->auModel->id_field = 'Supplier Delivery Key';
    }

    public function getParent(): Supplier|Agent
    {
        //print_r($this->auModel->data);
        if ($this->auModel->data->{'Supplier Delivery Parent'} == 'Agent') {
            return Agent::withTrashed()->where('aurora_id', $this->auModel->data->{'Supplier Delivery Parent Key'})->first();
        }

        $parent= $this->getSupplier($this->auModel->data->{'Supplier Delivery Parent Key'});
        if(!$parent){
            print  "Procurement delivery parent not found";
            dd($this->auModel->data);
        }
        return $parent;
    }

    public function parseModelData()
    {
        $date = $this->getDate($this->auModel->data->{'Supplier Delivery Date'});
        if (!$date) {
            $date = $this->getDate($this->auModel->data->{'Supplier Delivery Last Updated Date'});
        }

        $data = [
            'vendor' => [
                'code'    => $this->auModel->data->{'Supplier Delivery Parent Code'}.'-A',
                'name'    => $this->auModel->data->{'Supplier Delivery Parent Name'},
                'contact' => $this->auModel->data->{'Supplier Delivery Parent Contact Name'},
                'email'   => $this->auModel->data->{'Supplier Delivery Parent Email'},
                'phone'   => $this->auModel->data->{'Supplier Delivery Parent Telephone'},
                'address' => $this->auModel->data->{'Supplier Delivery Parent Address'},
            ]
        ];


        ksort($data);


        $this->modelData   = [
            'number'        => $this->auModel->data->{'Supplier Delivery Public ID'},
            'state'         => match ($this->auModel->data->{'Supplier Delivery State'}) {
                'InvoiceChecked' => 'costing-done',
                default => Str::snake($this->auModel->data->{'Supplier Delivery State'}, '-'),
            },
            'date'          => $date,
            'dispatched_at' => $this->auModel->data->{'Supplier Delivery Dispatched Date'},
            'received_at'   => $this->auModel->data->{'Supplier Delivery Received Date'},
            'placed_at'     => $this->auModel->data->{'Supplier Delivery Placed Date'},
            'cancelled_at'  => $this->auModel->data->{'Supplier Delivery Cancelled Date'},

            'data'      => $data,
            'aurora_id' => $this->auModel->data->{'Supplier Delivery Key'},

        ];



        $this->auModel->id = $this->auModel->data->{'Supplier Delivery Key'};
    }


    public function setModel()
    {
        $this->model = ProcurementDelivery::withTrashed()->find($this->auModel->data->aiku_id);
    }

    public function updateModel(): ActionResult
    {
        return UpdateProcurementDelivery::run(procurementDelivery: $this->model, modelData: $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StoreProcurementDelivery::run($this->parent, $this->modelData);
    }

    protected function migrateAttachments()
    {
        /** @var ProcurementDelivery $model */
        $model = $this->model;

        $auroraAttachmentsCollection               = $this->getModelAttachmentsCollection('Supplier Delivery', $model->aurora_id);
        $auroraAttachmentsCollectionWithAttachment = $auroraAttachmentsCollection->each(function ($auroraAttachment) {
            if ($attachment = MigrateCommonAttachment::run($auroraAttachment)) {
                return $auroraAttachment->common_attachment_id = $attachment->id;
            } else {
                return $auroraAttachment->common_attachment_id = null;
            }
        });

        MigrateAttachments::run($model, $auroraAttachmentsCollectionWithAttachment);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): ActionResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Supplier Delivery Dimension')->where('Supplier Delivery Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
