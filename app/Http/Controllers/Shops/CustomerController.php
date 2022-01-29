<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 22:41:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Shops;


use App\Actions\CRM\Customer\IndexCustomer;
use App\Actions\CRM\Customer\IndexCustomerInEcommerceShops;
use App\Actions\CRM\Customer\IndexCustomerInShop;
use App\Actions\CRM\Customer\ShowCustomer;
use App\Http\Controllers\Controller;
use App\Models\CRM\Customer;
use App\Models\Trade\Shop;
use Illuminate\Http\Request;
use Inertia\Response;


class CustomerController extends Controller
{


    public function index(Request $request): Response
    {
        return IndexCustomer::make()->asInertia($request);
    }

    public function indexInEcommerceShops(Request $request): Response
    {
        return indexCustomerInEcommerceShops::make()->asInertia();
    }

    public function indexInShop(Shop $shop): Response
    {
        return IndexCustomerInShop::make()->asInertia($shop);
    }


    public function show(Shop $shop,Customer $customer): Response
    {
        return ShowCustomer::make()->asInertia($customer);
    }


}
