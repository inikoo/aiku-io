<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 15:59:07 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Marketing;

use App\Actions\Marketing\Department\IndexDepartment;
use App\Actions\Marketing\Family\IndexFamilyInShop;
use App\Actions\Marketing\Product\IndexProductInShop;
use App\Actions\Marketing\Shop\IndexShop;
use App\Actions\Marketing\Shop\ShowShop;
use App\Http\Controllers\Controller;
use App\Models\Marketing\Shop;
use Illuminate\Http\Request;
use Inertia\Response;


class ShopController extends Controller
{


    public function index(): Response
    {
        return IndexShop::make()->asInertia();
    }

    public function catalogue(Shop $shop): Response
    {
        return match (session('catalogue')) {
            'families' => IndexFamilyInShop::make()->asInertia($shop),
            'products' => IndexProductInShop::make()->asInertia($shop),
            default => IndexDepartment::make()->asInertia($shop)
        };
    }

    public function setCatalogue(Shop $shop, Request $request): Response
    {

        session(['catalogue' => $request->input('catalogue', 'departments')]);


        return match ($request->input('catalogue')) {
            'families' => IndexFamilyInShop::make()->asInertia($shop),
            'products' => IndexProductInShop::make()->asInertia($shop),
            default => IndexDepartment::make()->asInertia($shop)
        };
    }


    public function show(Shop $shop): Response
    {
        return ShowShop::make()->asInertia($shop);
    }


}
