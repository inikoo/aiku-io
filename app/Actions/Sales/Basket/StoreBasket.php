<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 16 Nov 2021 00:35:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Basket;

use App\Actions\Migrations\MigrationResult;
use App\Models\CRM\Customer;
use App\Models\Helpers\Address;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreBasket
{
    use AsAction;

    public function handle(Customer $customer, array $data, ?Address $deliveryAddress): MigrationResult
    {
        $res = new MigrationResult();


        $data['currency_id']=$customer->shop->currency_id;

        /** @var \App\Models\Sales\Basket $basket */
        $basket = $customer->basket()->create($data);

        $addresses=[];

        if($deliveryAddress){
            $deliveryAddress->save();
            $addresses[$deliveryAddress->id] = ['scope' => 'delivery'];

        }else{
            $addresses[$customer->deliveryAddress->id] = ['scope' => 'delivery'];
        }


        $basket->deliveryAddress()->sync($addresses);

        $res->model    = $basket;
        $res->model_id = $basket->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }
}
