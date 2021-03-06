<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Oct 2021 22:19:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\Migrations\Traits\GetSupplier;
use App\Actions\Procurement\PurchaseOrder\StorePurchaseOrder;
use App\Actions\Procurement\PurchaseOrder\UpdatePurchaseOrder;
use App\Models\Procurement\Agent;
use App\Models\Procurement\PurchaseOrder;
use App\Models\Procurement\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Utils\ActionResult;

class MigratePurchaseOrder extends MigrateModel
{
    use AsAction;
    use GetSupplier;


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

        return $this->getSupplier($this->auModel->data->{'Purchase Order Parent Key'});

    }

    public function parseModelData()
    {
        $date = $this->getDate($this->auModel->data->{'Purchase Order Date'});
        if (!$date) {
            $date = $this->getDate($this->auModel->data->{'Purchase Order Last Updated Date'});
        }

        $data              = [
            'vendor' => [
                'code'    => $this->auModel->data->{'Purchase Order Parent Code'}.'-A',
                'name'    => $this->auModel->data->{'Purchase Order Parent Name'},
                'contact' => $this->auModel->data->{'Purchase Order Parent Contact Name'},
                'email'   => $this->auModel->data->{'Purchase Order Parent Email'},
                'phone'   => $this->auModel->data->{'Purchase Order Parent Telephone'},
                'address'   => $this->auModel->data->{'Purchase Order Parent Address'},
            ]
        ];


        ksort($data);

        //enum('Cancelled','NoReceived','InProcess','Submitted','Confirmed','Manufactured','QC_Pass','Inputted','Dispatched','Received','Checked','Placed','Costing','InvoiceChecked')

        $this->modelData   = [
            'number'       => $this->auModel->data->{'Purchase Order Public ID'},
            'state'        => match ($this->auModel->data->{'Purchase Order State'}) {
                'NoReceived' => 'cancelled',
                'Inputted' => 'confirmed',
                'Received','Checked','Placed','Costing','InvoiceChecked' => 'delivered',
                'Manufactured','QC_Pass' => null,
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

    public function updateModel(): ActionResult
    {
        return UpdatePurchaseOrder::run(purchaseOrder:$this->model,modelData: $this->modelData);
    }

    public function storeModel(): ActionResult
    {
        return StorePurchaseOrder::run($this->parent, $this->modelData);
    }

    protected function migrateAttachments()
    {

        /** @var PurchaseOrder $model */
        $model = $this->model;

        $auroraAttachmentsCollection               = $this->getModelAttachmentsCollection('Purchase Order', $model->aurora_id);
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
        if ($auroraData = DB::connection('aurora')->table('Purchase Order Dimension')->where('Purchase Order Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new ActionResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}
