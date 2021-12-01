<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 18:57:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Order;

use App\Actions\Migrations\MigrationResult;
use App\Models\Sales\Order;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteOrder
{
    use AsAction;

    public function handle(Order $order): MigrationResult
    {
        $res = new MigrationResult();
        $res->model_id = $order->id;

        $order->transactions()->delete();


        if ($order->delete()) {
            $res->status  ='deleted';
        }else{
            $res->status  ='error';

        }
        return $res;

    }
}
