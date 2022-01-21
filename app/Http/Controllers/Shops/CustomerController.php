<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 22:41:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Shops;


use App\Http\Controllers\Controller;
use App\Models\CRM\Customer;
use Inertia\Response;


class CustomerController extends Controller
{


    public function index(): Response
    {
        return IndexCustomer::make()->asInertia();
    }

    public function show(Customer $customer): Response
    {
        return ShowCustomer::make()->asInertia($customer);
    }


}
