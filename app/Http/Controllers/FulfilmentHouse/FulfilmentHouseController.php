<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:44:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\FulfilmentHouse;

use App\Actions\Trade\Shop\ShopIndex;
use App\Actions\Trade\Shop\ShowShop;
use App\Http\Controllers\Controller;
use App\Models\Trade\Shop;
use Inertia\Response;


class FulfilmentHouseController extends Controller
{


    public function index(): Response
    {
        return ShopIndex::make()->asInertia(module:'fulfilment_houses');
    }

    public function show(Shop $shop): Response
    {
        return ShowShop::make()->asInertia($shop,'fulfilment_house');
    }


}
