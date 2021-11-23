<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 15 Nov 2021 17:01:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Sales\Basket\DeleteBasket;
use App\Actions\Sales\Basket\StoreBasket;
use App\Actions\Sales\Basket\UpdateBasket;
use App\Models\CRM\Customer;
use App\Models\Helpers\Address;
use App\Models\Sales\Basket;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateBasket extends MigrateModel
{
    use AsAction;



    #[Pure] public function __construct()
    {
        parent::__construct();
        $this->auModel->table    = 'Order Dimension';
        $this->auModel->id_field = 'Order Key';
        $this->aiku_id_field     = 'aiku_basket_id';

    }

    public function getParent(): Customer
    {
        if ($this->auModel->data->{'Order Customer Client Key'} != '') {

            $parent = Customer::withTrashed()->firstWhere('aurora_customer_client_id', $this->auModel->data->{'Order Customer Client Key'});
        } else {

            $parent = Customer::withTrashed()->firstWhere('aurora_customer_id', $this->auModel->data->{'Order Customer Key'});
        }


        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        if ($parent->trashed() or  ($parent->shop->type=='dropshipping' and !$this->auModel->data->{'Order Customer Client Key'} ) ) {

            $this->ignore=true;
            DB::connection('aurora')->table($this->auModel->table)
                ->where($this->auModel->id_field, $this->auModel->data->{'Order Key'})
                ->update(['aiku_note' => 'ignore']);

        }


        return $parent;
    }


    public function parseModelData()
    {


        if($this->ignore){
            return;
        }


        $this->modelData['basket'] = [
            'nickname'  => $this->auModel->data->{'Order Public ID'},
            'aurora_id' => $this->auModel->data->{'Order Key'},

        ];
        $this->auModel->id         = $this->auModel->data->{'Order Key'};

        $deliveryAddressData = $this->parseAddress(prefix: 'Order Delivery', auAddressData: $this->auModel->data);

        $deliveryAddress = new Address($deliveryAddressData);




        $this->modelData['delivery_address'] = null;
        if ($deliveryAddress->getChecksum() != $this->parent->deliveryAddress->getChecksum()) {
            $this->modelData['delivery_address'] = $deliveryAddress;
        }
    }


    public function setModel()
    {
        $this->model = Basket::find($this->auModel->data->aiku_basket_id);
    }

    public function updateModel(): MigrationResult
    {
        if ($this->auModel->data->{'Order State'} == 'InBasket' and !$this->ignore) {
            return UpdateBasket::run($this->model, $this->modelData['basket'], $this->modelData['delivery_address']);
        } else {
            return DeleteBasket::run($this->model);
        }
    }

    public function storeModel(): MigrationResult
    {
        if ($this->auModel->data->{'Order State'} == 'InBasket' and !$this->ignore) {
            return StoreBasket::run($this->parent, $this->modelData['basket'], $this->modelData['delivery_address']);
        }

        return new MigrationResult();
    }

    protected function postMigrateActions(MigrationResult $res): MigrationResult
    {
        if($this->ignore){
            $res = new MigrationResult();
            $res->status   = 'error';
            return $res;
        }

        foreach (
            DB::connection('aurora')->table('Order Transaction Fact')
                ->where('Order Key', $this->auModel->data->{'Order Key'})
                ->get() as $auroraTransaction
        ) {
            MigrateBasketProductTransaction::run($auroraTransaction);
        }
        foreach (
            DB::connection('aurora')->table('Order No Product Transaction Fact')
                ->where('Order Key', $this->auModel->data->{'Order Key'})
                ->get() as $auroraTransaction
        ) {
            MigrateBasketNoProductTransaction::run($auroraTransaction);
        }

        return $res;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->tokenCan('root');
    }

    public function asController(int $auroraID): MigrationResult
    {
        $this->setAuroraConnection(app('currentTenant')->data['aurora_db']);
        if ($auroraData = DB::connection('aurora')->table('Order Dimension')->where('Order Key', $auroraID)->first()) {
            return $this->handle($auroraData);
        }
        $res           = new MigrationResult();
        $res->errors[] = 'Aurora model not found';
        $res->status   = 'error';

        return $res;
    }
}




