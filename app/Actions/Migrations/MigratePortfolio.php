<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 15 Mar 2022 17:51:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Migrations;

use App\Actions\CRM\Customer\Portfolio\SyncPortfolio;
use App\Actions\Migrations\Traits\GetProduct;
use App\Models\CRM\Customer;
use App\Models\Utils\ActionResult;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class MigratePortfolio
{
    use AsAction;
    use GetProduct;

    public function handle(Customer $customer): ActionResult
    {
        $products = [];

        if($customer->aurora_id) {
            foreach (
                DB::connection('aurora')
                    ->table('Customer Portfolio Fact')
                    ->where('Customer Portfolio Customer Key', $customer->aurora_id)->get() as $auroraPortfolio
            ) {
                $product = $this->getProduct($auroraPortfolio->{'Customer Portfolio Product ID'});


                if ($product) {
                    $products[$product->id] =
                        [
                            'status'             => match ($auroraPortfolio->{'Customer Portfolio Customers State'}) {
                                'Active' => true,
                                default => false
                            },
                            'created_at'         => $auroraPortfolio->{'Customer Portfolio Creation Date'},
                            'removed_at'         => match ($auroraPortfolio->{'Customer Portfolio Customers State'}) {
                                'Removed' => $auroraPortfolio->{'Customer Portfolio Removed Date'},
                                default => null
                            },
                            'customer_reference' => $auroraPortfolio->{'Customer Portfolio Reference'},
                            'data'               => [],
                            'settings'           => [],
                            'aurora_id'          => $auroraPortfolio->{'Customer Portfolio Key'},
                        ];
                }
            }
        }
        $res = SyncPortfolio::run($customer, $products);

        foreach ($customer->portfolio as $customerProduct) {
            DB::connection('aurora')->table('Customer Portfolio Fact')
                ->where('Customer Portfolio Key', $customerProduct->pivot->aurora_id)
                ->update(['aiku_id' => $customerProduct->pivot->id]);
        }

        return $res;
    }
}
