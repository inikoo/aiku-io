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
use App\Models\Sales\TaxBand;
use App\Models\Trade\Product;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateBasketProductTransaction extends MigrateModel
{
    use AsAction;


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
        $taxBand = (new TaxBand())->firstWhere('aurora_id', $this->auModel->data->{'Order Transaction Tax Category Key'});

        $product = (new Product())->firstWhere('aurora_product_id', $this->auModel->data->{'Product ID'});

        $this->modelData   = [
            'item_type' => 'Product',
            'item_id'   => $product->id,
            'tax_band_id' => $taxBand->id ?? null,

            'quantity'  => $this->auModel->data->{'Order Quantity'},
            'discounts' => $this->auModel->data->{'Order Transaction Total Discount Amount'},
            'net'       => $this->auModel->data->{'Order Transaction Amount'},
            'aurora_id' => $this->auModel->data->{'Order Transaction Fact Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Order Transaction Fact Key'};
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




