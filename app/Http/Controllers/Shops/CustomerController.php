<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 22:41:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Shops;


use App\Actions\CRM\Customer\IndexCustomerInShop;
use App\Actions\CRM\Customer\IndexCustomerInTenant;
use App\Actions\CRM\Customer\ShowCustomer;
use App\Http\Controllers\Controller;
use App\Models\CRM\Customer;
use App\Models\Trade\Shop;
use Inertia\Response;


class CustomerController extends Controller
{


    public function index(): Response
    {
        return IndexCustomerInTenant::make()->asInertia();
    }



    public function indexInShop(Shop $shop)
    {
        return IndexCustomerInShop::make()->asInertia($shop);
    }


    public function showInShop(Shop $shop, Customer $customer): Response
    {
        return ShowCustomer::make()->asInertia($customer);
    }


}
