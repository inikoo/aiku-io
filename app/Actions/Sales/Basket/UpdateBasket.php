<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 16 Nov 2021 02:08:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Basket;

use App\Actions\Migrations\MigrationResult;
use App\Actions\WithUpdate;
use App\Models\Helpers\Address;
use App\Models\Sales\Basket;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBasket
{
    use AsAction;
    use WithUpdate;

    public function handle(Basket $basket, array $modelData, ?Address $deliveryAddress): MigrationResult
    {
        $res = new MigrationResult();


        $addresses=[];

        if($deliveryAddress){
            $deliveryAddress->save();
            $addresses[$deliveryAddress->id] = ['scope' => 'delivery'];

        }else{
            $addresses[$basket->customer->deliveryAddress->id] = ['scope' => 'delivery'];
        }



        $basket->deliveryAddress()->sync($addresses);


        $basket->update( Arr::except($modelData, ['data']));
        $basket->update($this->extractJson($modelData));

        $res->changes = array_merge($res->changes, $basket->getChanges());





        $res->model    = $basket;
        $res->model_id = $basket->id;
        $res->status   = $res->changes ? 'updated' : 'unchanged';

        return $res;
    }
}
