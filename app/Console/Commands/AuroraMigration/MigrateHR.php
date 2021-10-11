<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 00:04:35 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateDeletedEmployee;
use App\Actions\Migrations\MigrateEmployee;
use App\Actions\Migrations\MigrateUser;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;


class MigrateHR extends MigrateAurora
{


    protected $signature = 'au_migration:hr {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora human resources';


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
        DB::connection('aurora')->table('Staff Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Staff Deleted Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('User Dimension')->where('User Type', 'Staff')
            ->update(['aiku_id' => null]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Staff Dimension')->count();
        $count += DB::connection('aurora')->table('Staff Deleted Dimension')->count();
        $count += DB::connection('aurora')->table('User Dimension')->where('User Type', 'Staff')->count();
        return $count;
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Staff Dimension')->get() as $auroraData) {
            $this->results[$tenant->slug]['models']++;
            $result = MigrateEmployee::run($auroraData);
            $this->recordAction($tenant, $result);
        }

        foreach (DB::connection('aurora')->table('Staff Deleted Dimension')->get() as $auroraData) {
            $this->results[$tenant->slug]['models']++;

            $result = MigrateDeletedEmployee::run($auroraData);
            $this->recordAction($tenant, $result);
        }

        foreach (
            DB::connection('aurora')->table('User Dimension')
                ->where('User Type', 'Staff')
                ->get() as $auroraUserData
        ) {
            $this->results[$tenant->slug]['models']++;
            $result = MigrateUser::run($auroraUserData);
            $this->recordAction($tenant, $result);
        }
    }


}