<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Feb 2022 01:20:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Marketing;


use App\Actions\CRM\Customer\IndexCustomerInFulfilmentShop;
use App\Actions\CRM\Customer\IndexCustomerInShop;
use App\Actions\CRM\Customer\IndexCustomerInTenant;
use App\Actions\CRM\Customer\ShowCustomerInShop;
use App\Http\Controllers\Controller;
use App\Models\CRM\Customer;
use App\Models\Marketing\Shop;
use Inertia\Response;


class CustomerController extends Controller
{


    public function index(): Response
    {
        return IndexCustomerInTenant::make()->asInertia();
    }



    public function indexInShop(Shop $shop)
    {
        if($shop->type=='fulfilment_house'){
            return IndexCustomerInFulfilmentShop::make()->asInertia($shop);
        }
        return IndexCustomerInShop::make()->asInertia($shop);
    }



    public function showInShop(Shop $shop, Customer $customer): Response
    {
        return ShowCustomerInShop::make()->asInertia($customer);
    }


}
