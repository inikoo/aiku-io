<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 20:08:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Basket;

use App\Actions\Migrations\MigrationResult;
use App\Models\Sales\Basket;
use Lorisleiva\Actions\Concerns\AsAction;

class DestroyBasket
{
    use AsAction;

    public function handle(Basket $basket): MigrationResult
    {
        $res = new MigrationResult();
        $res->model_id = $basket->id;

        $basket->transactions()->forceDelete();


        if ($basket->forceDelete()) {
            $res->status  ='deleted';
        }else{
            $res->status  ='error';

        }
        return $res;

    }
}
