<?php
/** @noinspection DuplicatedCode */

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Nov 2021 15:24:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Console\Commands\AuroraMigration;

use App\Actions\Migrations\MigrateDeletedInvoice;
use App\Actions\Migrations\MigrateDeliveryNote;
use App\Actions\Migrations\MigrateInvoice;
use App\Actions\Migrations\MigrateOrder;
use App\Models\Account\Tenant;
use Illuminate\Support\Facades\DB;

class MigrateOrders extends MigrateAurora
{
    protected $signature = 'au_migration:orders {--reset} {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Migrate aurora consolidated orders';

    public function handle(): int
    {
        $this->handleMigration();

        return 0;
    }

    protected function reset()
    {
        DB::connection('aurora')->table('Order Dimension')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Order Transaction Fact')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Order No Product Transaction Fact')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Order Dimension')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Order Transaction Fact')->update(
            [
                'aiku_id'         => null,
                'aiku_invoice_id' => null,
            ]
        );
        DB::connection('aurora')->table('Order No Product Transaction Fact')->update(
            [
                'aiku_id'         => null,
                'aiku_invoice_id' => null,
            ]
        );

        DB::connection('aurora')->table('Invoice Dimension')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Invoice Deleted Dimension')->update(['aiku_id' => null]);
        DB::connection('aurora')->table('Delivery Note Dimension')->update(['aiku_id' => null]);

        DB::connection('aurora')->table('Inventory Transaction Fact')->update(
            [
                'aiku_dn_item_id' => null,
                'aiku_invoice_id' => null,
            ]
        );
    }

    protected function count(): int
    {
        $count = DB::connection('aurora')->table('Order Dimension')
            ->whereNull('aiku_note')
            ->count();
        $count += DB::connection('aurora')->table('Invoice Dimension')
            ->count();
        $count += DB::connection('aurora')->table('Invoice Deleted Dimension')
            ->count();
        $count += DB::connection('aurora')->table('Delivery Note Dimension')
            ->count();

        return $count;
    }


    protected function migrate(Tenant $tenant)
    {
        DB::connection('aurora')->table('Order Dimension')
            ->whereNull('aiku_note')
            ->orderBy('Order Created Date')->chunk(10000, function ($chunk) use ($tenant) {
                foreach ($chunk as $auroraData) {
                    $result = MigrateOrder::run($auroraData);
                    $this->recordAction($tenant, $result);


                    DB::connection('aurora')->table('Invoice Dimension')
                        ->where('Invoice Order Key', $auroraData->{'Order Key'})
                        ->orderBy('Invoice Key')->chunk(1000, function ($chunk) use ($tenant) {
                            foreach ($chunk as $auroraInvoiceData) {
                                $result = MigrateInvoice::run($auroraInvoiceData);
                                $this->recordAction($tenant, $result);
                            }
                        });
                    DB::connection('aurora')->table('Invoice Deleted Dimension')
                        ->where('Invoice Deleted Order Key', $auroraData->{'Order Key'})
                        ->orderBy('Invoice Deleted Key')->chunk(1000, function ($chunk) use ($tenant) {
                            foreach ($chunk as $auroraInvoiceData) {
                                $result = MigrateDeletedInvoice::run($auroraInvoiceData);
                                $this->recordAction($tenant, $result);
                            }
                        });


                    DB::connection('aurora')->table('Delivery Note Dimension')
                        ->where('Delivery Note Order Key', $auroraData->{'Order Key'})
                        ->orderBy('Delivery Note Key')->chunk(1000, function ($chunk) use ($tenant) {
                            foreach ($chunk as $auroraDeliveryNoteData) {
                                $result = MigrateDeliveryNote::run($auroraDeliveryNoteData);
                                $this->recordAction($tenant, $result);
                            }
                        });
                }
            });
    }


}
