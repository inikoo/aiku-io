<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 00:04:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateShop;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateShops extends MigrateAurora
{

    protected $signature = 'au_migration:shops {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora stores resources';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Store Dimension')
            ->update(['aiku_id' => null]);
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Store Dimension')->get() as $auroraData) {

            $result=MigrateShop::run($auroraData);
            $this->recordAction($tenant,$result);

        }
    }



}
