<?php /*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 01 Mar 2022 16:29:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */ /** @noinspection PhpUnusedParameterInspection */

namespace App\Http\Controllers\Inventory;



use App\Actions\Inventory\UniqueStock\IndexUniqueStockInCustomer;
use App\Actions\Inventory\UniqueStock\IndexUniqueStockInTenant;
use App\Http\Controllers\Controller;
use App\Models\CRM\Customer;
use App\Models\Inventory\UniqueStock;
use App\Models\Marketing\Shop;
use Inertia\Response;


class UniqueStockController extends Controller
{

    public function indexUniqueStockInTenant(): Response
    {
        return IndexUniqueStockInTenant::make()->asInertia();
    }

    public function indexUniqueStockInShop(Shop $shop): Response
    {
        return IndexUniqueStockInShop::make()->asInertia($shop);
    }

    public function indexUniqueStocksInCustomer(Shop $shop, Customer $customer): Response
    {
        return IndexUniqueStockInCustomer::make()->asInertia($customer);
    }

    public function showUniqueStock(UniqueStock $uniqueStock): Response
    {
        return ShowUniqueStockInTenant::make()->asInertia($uniqueStock);
    }
    public function showUniqueStockInShop(Shop $shop,UniqueStock $uniqueStock): Response
    {
        return ShowUniqueStockInShop::make()->asInertia($uniqueStock);
    }

    public function showUniqueStockInCustomer(Shop $shop, Customer $customer,UniqueStock $uniqueStock): Response
    {
        return ShowUniqueStockInCustomer::make()->asInertia($uniqueStock);
    }



}
