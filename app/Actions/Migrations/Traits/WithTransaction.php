<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Mar 2022 23:12:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations\Traits;


use App\Actions\Sales\Adjust\StoreAdjust;
use App\Models\Marketing\Shop;
use App\Models\Sales\Adjust;
use App\Models\Sales\Charge;
use App\Models\Sales\ShippingZone;
use App\Models\Sales\TaxBand;

use function dd;

trait WithTransaction
{
    use GetProduct;

    public function parseNoProductTransactionData()
    {
        $taxBand = (new TaxBand())->firstWhere('aurora_id', $this->auModel->data->{'Order No Product Transaction Tax Category Key'});


        switch ($this->auModel->data->{'Transaction Type'}) {
            case 'Shipping':
                $item      = (new ShippingZone())->firstWhere('aurora_id', $this->auModel->data->{'Transaction Type Key'});
                $item_type = 'ShippingZone';
                break;
            case 'Charges':
                $item      = (new Charge())->firstWhere('aurora_id', $this->auModel->data->{'Transaction Type Key'});
                $item_type = 'Charges';

                break;
            case 'Insurance':
                $item      = (new Charge())->where('type', 'insurance')->where('shop_id', $this->parent->shop_id)->first();
                $item_type = 'Charges';
                break;
            case 'Premium':
                $item      = (new Charge())->where('type', 'premium')->where('shop_id', $this->parent->shop_id)->first();
                $item_type = 'Charges';
                break;
            case 'Refund':
            case 'Credit':
            case 'Adjust':


            $adjustType=    match ($this->auModel->data->{'Transaction Type'}) {
                'Adjust' => 'other',
                default => strtolower($this->auModel->data->{'Transaction Type'})
            };


            $shop = Shop::withTrashed()->find($this->parent->shop_id);

            $item = (new Adjust())->where('type',$adjustType)->where('shop_id', $this->parent->shop_id)->first();



                if (!$item) {
                    $res  = StoreAdjust::run($shop,
                                             [
                                                 'type' =>$adjustType
                                             ]
                    );
                    $item = $res->model;
                }

                $item_type = 'Adjust';


                break;


            default:
                print "===== MigrateNoProductTransaction.php\n";
                dd($this->auModel->data);
        }

        //if(!$item){
        //    dd($this->auModel->data);
        //}

        $this->modelData   = [
            'item_type'            => $item_type,
            'tax_band_id'          => $taxBand->id ?? null,
            'item_id'              => $item->id ?? null,
            'quantity'             => 1,
            'discounts'            => $this->auModel->data->{'Transaction Total Discount Amount'},
            'net'                  => $this->auModel->data->{'Transaction Net Amount'},
            'aurora_no_product_id' => $this->auModel->data->{'Order No Product Transaction Fact Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Order No Product Transaction Fact Key'};
    }

    public function parseProductTransactionData()
    {
        $taxBand = (new TaxBand())->firstWhere('aurora_id', $this->auModel->data->{'Order Transaction Tax Category Key'});


        $product=$this->getProduct($this->auModel->data->{'Product ID'});

        if(!$product){
            dd('Product not found while migrating the order');
        }

        $this->modelData   = [
            'item_type'   => 'Product',
            'item_id'     => $product->id,
            'tax_band_id' => $taxBand->id ?? null,

            'quantity'  => $this->auModel->data->{'Order Quantity'},
            'discounts' => $this->auModel->data->{'Order Transaction Total Discount Amount'},
            'net'       => $this->auModel->data->{'Order Transaction Amount'},
            'aurora_id' => $this->auModel->data->{'Order Transaction Fact Key'},

        ];
        $this->auModel->id = $this->auModel->data->{'Order Transaction Fact Key'};
    }
}


