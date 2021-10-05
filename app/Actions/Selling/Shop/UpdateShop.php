<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 17:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Selling\Shop;

use App\Models\Selling\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateShop
{
    use AsAction;

    public function handle(Shop $shop, array $data): Shop
    {
        $shop->update($data);

        return $shop;
    }
}
