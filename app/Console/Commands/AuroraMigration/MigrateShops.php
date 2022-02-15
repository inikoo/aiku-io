<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 25 Sep 2021 00:04:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateCharge;
use App\Actions\Migrations\MigrateDepartment;
use App\Actions\Migrations\MigrateFamily;
use App\Actions\Migrations\MigrateShipper;
use App\Actions\Migrations\MigrateShippingSchema;
use App\Actions\Migrations\MigrateShop;
use App\Actions\Migrations\MigrateWebsite;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateShops extends MigrateAurora
{

    protected $signature = 'au_migration:shops {--reset} {--all} {--t|tenant=* : Tenant slug} ';
    protected $description = 'Migrate aurora stores resources';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Store Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Shipping Zone Schema Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Shipping Zone Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Charge Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Shipper Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Website Dimension')
            ->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Category Dimension')
            ->update([
                         'aiku_department_id' => null,
                         'aiku_family_id' => null
                     ]);
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Store Dimension')->count();
        $count += DB::connection('aurora')->table('Shipping Zone Schema Dimension')->count();
        $count += DB::connection('aurora')->table('Charge Dimension')->count();
        $count += DB::connection('aurora')->table('Shipper Dimension')->count();
        $count += DB::connection('aurora')->table('Website Dimension')->count();
        foreach (DB::connection('aurora')->table('Store Dimension')->get() as $auroraStoreData) {
            $count += DB::connection('aurora')
                ->table('Category Dimension')
                ->where('Category Branch Type', 'Head')
                ->where('Category Root Key', $auroraStoreData->{'Store Department Category Key'})->count();
        }
        foreach (DB::connection('aurora')->table('Store Dimension')->get() as $auroraStoreData) {
            $count += DB::connection('aurora')
                ->table('Category Dimension')
                ->where('Category Branch Type', 'Head')
                ->where('Category Root Key', $auroraStoreData->{'Store Family Category Key'})->count();
        }

        return $count;
    }

    protected function migrate(Tenant $tenant)
    {
        foreach (DB::connection('aurora')->table('Store Dimension')->get() as $auroraStoreData) {
            $result = MigrateShop::run($auroraStoreData);
            $this->recordAction($tenant, $result);

            foreach (
                DB::connection('aurora')->table('Shipping Zone Schema Dimension')
                    ->where('Shipping Zone Schema Store Key', $auroraStoreData->{'Store Key'})
                    ->get() as $auroraShippingSchemaData
            ) {
                $result = MigrateShippingSchema::run($auroraShippingSchemaData);
                $this->recordAction($tenant, $result);
            }

            foreach (
                DB::connection('aurora')->table('Charge Dimension')
                    ->where('Charge Store Key', $auroraStoreData->{'Store Key'})
                    ->get() as $auroraChargeData
            ) {
                $result = MigrateCharge::run($auroraChargeData);
                $this->recordAction($tenant, $result);
            }

            foreach (
                DB::connection('aurora')
                    ->table('Category Dimension')
                    ->leftJoin('Product Category Dimension', 'Product Category Key', 'Category Key')
                    ->where('Category Branch Type', 'Head')
                    ->where('Category Root Key', $auroraStoreData->{'Store Department Category Key'})->get() as $auroraDepartment
            ) {
                $result = MigrateDepartment::run($auroraDepartment);
                $this->recordAction($tenant, $result);
            }
            foreach (
                DB::connection('aurora')
                    ->table('Category Dimension')
                    ->leftJoin('Product Category Dimension', 'Product Category Key', 'Category Key')
                    ->where('Category Branch Type', 'Head')
                    ->where('Category Root Key', $auroraStoreData->{'Store Family Category Key'})->get() as $auroraFamily
            ) {
                $result = MigrateFamily::run($auroraFamily);
                $this->recordAction($tenant, $result);
            }
        }

        foreach (DB::connection('aurora')->table('Shipper Dimension')->get() as $auroraShipperData) {
            $result = MigrateShipper::run($auroraShipperData);
            $this->recordAction($tenant, $result);
        }

        foreach (DB::connection('aurora')->table('Website Dimension')->get() as $auroraWebsiteData) {
            $result = MigrateWebsite::run($auroraWebsiteData);
            $this->recordAction($tenant, $result);
        }
    }


}
