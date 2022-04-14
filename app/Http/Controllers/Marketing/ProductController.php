<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 14 Apr 2022 23:24:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Marketing;


use App\Actions\Marketing\Product\IndexProductInShop;
use App\Http\Controllers\Controller;
use App\Models\Marketing\Shop;
use Inertia\Response;


class ProductController extends Controller
{


    public function indexInShop(Shop $shop): Response
    {
        return IndexProductInShop::make()->asInertia($shop);
    }

    public function showInShop(Shop $shop): Response
    {
        return ShowProduct::make()->asInertia($shop);
    }


}
