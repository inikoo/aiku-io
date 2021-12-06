<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 21:58:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;


use App\Actions\Sales\Adjust\StoreAdjust;
use App\Models\Sales\Adjust;
use App\Models\Sales\Charge;
use App\Models\Sales\ShippingZone;
use App\Models\Sales\TaxBand;
use App\Models\Trade\Product;
use App\Models\Trade\Shop;

trait WithTransaction
{
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


                $item = (new Adjust())->where('type', 'refund')->where('shop_id', $this->parent->shop_id)->first();
                $shop = Shop::withTrashed()->find($this->parent->shop_id);



                if (!$item) {
                    $res  = StoreAdjust::run($shop,
                                             [
                                                 'type' =>
                                                     match ($this->auModel->data->{'Transaction Type'}) {
                                                         'Adjust' => 'other',
                                                         default => strtolower($this->auModel->data->{'Transaction Type'})
                                                     },

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

        $product = (new Product())->firstWhere('aurora_product_id', $this->auModel->data->{'Product ID'});

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


