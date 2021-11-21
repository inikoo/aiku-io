<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 14 Nov 2021 02:02:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateBasket;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateBaskets extends MigrateAurora
{
    protected $signature = 'au_migration:baskets {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora orders in basket';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Order Dimension')->update(['aiku_basket_id' => null]);
        DB::connection('aurora')->table('Order Transaction Fact')->update(['aiku_basket_id' => null]);
        DB::connection('aurora')->table('Order No Product Transaction Fact')->update(['aiku_basket_id' => null]);

        DB::connection('aurora')->table('Order Dimension')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Order Transaction Fact')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Order No Product Transaction Fact')->update(['aiku_id' => null]);

    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Order Dimension')
            ->where('Order State', 'InBasket')
            ->count();
        $count += DB::connection('aurora')->table('Order Dimension')
            ->where('Order State', '!=','InBasket')
            ->whereNotNull('aiku_basket_id')
            ->count();

        return $count;
    }


    protected function migrate(Tenant $tenant)
    {
        DB::connection('aurora')->table('Order Dimension')
            ->where('Order State', '!=','InBasket')
            ->whereNotNull('aiku_basket_id')
            ->orderByDesc('Order Key')->chunk(1000, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {
                    $result = MigrateBasket::run($auroraData);
                    $this->recordAction($tenant, $result);
                }
            });

        DB::connection('aurora')->table('Order Dimension')
            ->where('Order State','InBasket')
            ->orderByDesc('Order Key')->chunk(1000, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {
                    $result = MigrateBasket::run($auroraData);
                    $this->recordAction($tenant, $result);
                }
            });

    }


}
