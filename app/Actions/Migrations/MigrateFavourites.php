<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 11 Mar 2022 15:39:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\Customer\Favourites\SyncFavourites;
use App\Actions\Migrations\Traits\GetProduct;
use App\Models\CRM\Customer;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MigrateFavourites
{
    use AsAction;
    use GetProduct;

    public function handle(Customer $customer): ActionResult
    {
        $products = [];
        foreach (
            DB::connection('aurora')
                ->table('Customer Favourite Product Fact')
                ->where('Customer Favourite Product Customer Key', $customer->aurora_id)->get() as $auroraFavourites
        ) {

            $product= $this->getProduct($auroraFavourites->{'Customer Favourite Product Product ID'});



            if ($product) {
                $products[$product->id] =
                    [
                        'created_at' => $auroraFavourites->{'Customer Favourite Product Creation Date'},
                        'aurora_id'  => $auroraFavourites->{'Customer Favourite Product Key'},

                    ];
            }
        }

        return SyncFavourites::run($customer,$products);
    }
}
