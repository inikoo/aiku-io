<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 12 Nov 2021 16:33:30 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;


use App\Actions\Migrations\MigrateTaxBand;
use Illuminate\Support\Facades\DB;

class MigrateTaxBands extends MigrateAurora
{
    protected $signature = 'au_migration:tax_bands {--reset}';
    protected $description = 'Migrate tax bands';

    public function handle(): int
    {
        $this->handleLandlordMigration();

        return 0;
    }



    protected function reset()
    {

        DB::connection('aurora')->table('Tax Category Dimension')
            ->update(['aiku_id' => null]);

    }

    protected function count(): int
    {
        return DB::connection('aurora')->table('Tax Category Dimension')->count();
    }


    protected function migrateLandlord()
    {


        foreach (DB::connection('aurora')->table('Tax Category Dimension')->get() as $auroraData) {
            $result = MigrateTaxBand::run($auroraData);
            $this->recordLandlordAction( $result);
        }

    }


}
