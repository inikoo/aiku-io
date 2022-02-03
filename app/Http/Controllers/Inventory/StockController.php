<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 16:48:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Inventory;


use App\Actions\Inventory\Stock\IndexStock;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Stock;
use Illuminate\Http\Request;
use Inertia\Response;


class StockController extends Controller
{


    public function index(): Response
    {
        return IndexStock::make()->asInertia();
    }

    public function show(Stock $stock, Request $request): Response
    {
        return ShowStock::make()->asInertia($stock, $request);
    }


}
