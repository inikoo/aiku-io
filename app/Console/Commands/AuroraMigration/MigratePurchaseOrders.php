<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Oct 2021 21:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;



use App\Actions\Migrations\MigratePurchaseOrder;

use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigratePurchaseOrders extends MigrateAurora
{

    protected $signature = 'au_migration:pos {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora purchase orders and supplier deliveries';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Purchase Order Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Attachment Bridge')->where('Subject','Purchase Order')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        return DB::connection('aurora')->table('Purchase Order Dimension')->count();
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Purchase Order Dimension')->get() as $auData) {
            $result = MigratePurchaseOrder::run($auData);
            $this->recordAction($tenant, $result);
        }
    }


}
