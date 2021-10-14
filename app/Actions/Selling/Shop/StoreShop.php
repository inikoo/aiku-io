<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 11:45:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Selling\Shop;

use App\Models\Selling\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreShop
{
    use AsAction;

    public function handle(array $data, array $contactData): Shop
    {
        $shop = Shop::create($data);
        $shop->contact()->create($contactData);

        return $shop;
    }


}
