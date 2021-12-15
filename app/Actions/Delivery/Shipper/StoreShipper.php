<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 05 Dec 2021 02:07:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Delivery\Shipper;

use App\Models\Utils\ActionResult;
use App\Models\Delivery\Shipper;
use Lorisleiva\Actions\Concerns\AsAction;


class StoreShipper
{
    use AsAction;

    public function handle(array $data, array $contactData): ActionResult
    {
        $res  = new ActionResult();
        $shipper = Shipper::create($data);
        $shipper->contact()->create($contactData);



        $res->model    = $shipper;
        $res->model_id = $shipper->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;



    }


}
