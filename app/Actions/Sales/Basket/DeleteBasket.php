<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 16 Nov 2021 04:20:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Basket;

use App\Actions\Migrations\MigrationResult;
use App\Models\Sales\Basket;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteBasket
{
    use AsAction;

    public function handle(Basket $basket): MigrationResult
    {
        $res = new MigrationResult();
        $res->model_id = $basket->id;

        $basket->transactions()->delete();


        if ($basket->delete()) {
            $res->status  ='deleted';
        }else{
            $res->status  ='error';

        }
        return $res;

    }
}
