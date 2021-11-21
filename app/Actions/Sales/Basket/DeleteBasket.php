<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 16 Nov 2021 04:20:34 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Sales\Basket;

use App\Models\Sales\Basket;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteBasket
{
    use AsAction;

    public function handle(Basket $basket): ?bool
    {
        return $basket->delete();
    }
}
