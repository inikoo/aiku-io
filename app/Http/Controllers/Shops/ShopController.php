<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:41:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Shops;

use App\Actions\Trade\Shop\IndexShop;
use App\Actions\Trade\Shop\ShowShop;
use App\Http\Controllers\Controller;
use App\Models\Trade\Shop;
use Inertia\Response;


class ShopController extends Controller
{


    public function index(): Response
    {
        return IndexShop::make()->asInertia(module: 'shops');
    }

    public function show(Shop $shop): Response
    {
        return ShowShop::make()->asInertia($shop, 'shop');
    }


}
