<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 15:59:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Trade;

use App\Actions\Trade\Shop\IndexEcommerceShop;
use App\Actions\Trade\Shop\ShowEcommerceShop;
use App\Http\Controllers\Controller;
use App\Models\Trade\Shop;
use Inertia\Response;


class EcommerceController extends Controller
{


    public function index(): Response
    {
        return IndexEcommerceShop::make()->asInertia();
    }

    public function show(Shop $shop): Response
    {
        return ShowEcommerceShop::make()->asInertia($shop);
    }


}
