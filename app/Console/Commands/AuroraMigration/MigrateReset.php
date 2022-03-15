<?php
/** @noinspection ALL */


namespace App\Console\Commands\AuroraMigration;


use App\Models\Account\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MigrateReset extends Command
{
    protected $signature = 'au_migration:reset  {--all} {--t|tenant=* : Tenant slug}';
    protected $description = 'Reset migrate aurora customers';

    private function setAuroraConnection($database_name)
    {
        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database_name);
        config(['database.connections.aurora' => $database_settings]);
        DB::connection('aurora');
        DB::purge('aurora');
    }

    public function handle(): int
    {
        if (!$this->option('all') and count($this->option('tenant')) == 0) {
            $this->error('Provide tenant --tenant or --all');
        }

        if ($this->option('all')) {
            $tenants = Tenant::whereNotNull('data->aurora_db')->orderByDesc('id')->get();
        } else {
            $tenants = Tenant::whereIn('code', $this->option('tenant'))->get();
        }


        $tenants->each(function ($tenant) {
            $tenant->makeCurrent();
            $this->line("🏃 $tenant->code");
            if (Arr::get($tenant->data, 'aurora_db')) {
                $this->setAuroraConnection($tenant->data['aurora_db']);

                $this->timeStart = microtime(true);
                $this->timeLastStep =  microtime(true);


                DB::connection('aurora')->table('User Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('User Deleted Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Attachment Bridge')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Image Subject Bridge')
                    ->update(['aiku_id' => null]);

                $this->line('✅ base');

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
                                 'aiku_family_id'     => null
                             ]);

                $this->line('✅ shops');

                DB::connection('aurora')->table('Warehouse Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Warehouse Area Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Location Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Location Deleted Dimension')
                    ->update(['aiku_id' => null]);

                DB::connection('aurora')->table('Fulfilment Rent Transaction Fact')
                    ->update(['aiku_id' => null]);

                DB::connection('aurora')->table('Fulfilment Asset Dimension')
                    ->update(['aiku_id' => null]);

                $this->line("✅ warehouses \t\t".$this->stepTime());


                DB::connection('aurora')->table('Supplier Dimension')->whereNotIn('Supplier Type', ['Agent'])
                    ->update(
                        [
                            'aiku_id'          => null,
                            'aiku_workshop_id' => null,
                        ]
                    );
                DB::connection('aurora')->table('Supplier Deleted Dimension')
                    ->update(
                        [
                            'aiku_id'          => null,
                            'aiku_workshop_id' => null,
                        ]
                    );


                $this->line("✅ suppliers \t\t".$this->stepTime());

                DB::connection('aurora')->table('Staff Dimension')
                    ->update(
                        [
                            'aiku_id'       => null,
                            'aiku_guest_id' => null
                        ]
                    );
                DB::connection('aurora')->table('Staff Deleted Dimension')
                    ->update(
                        [
                            'aiku_id'       => null,
                            'aiku_guest_id' => null
                        ]
                    );

                DB::connection('aurora')->table('Timesheet Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Timesheet Record Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Clocking Machine Dimension')
                    ->update(['aiku_id' => null]);
                $this->line("✅ HR \t\t\t".$this->stepTime());

                DB::connection('aurora')->table('Part Dimension')
                    ->update([
                                 'aiku_unit_id' => null,
                                 'aiku_id'      => null
                             ]);

                DB::connection('aurora')->table('Part Deleted Dimension')
                    ->update(['aiku_id' => null]);

                $this->line("✅ invetory \t\t".$this->stepTime());
                DB::connection('aurora')->table('Supplier Part Dimension')
                    ->update(
                        [
                            'aiku_supplier_id' => null,
                            'aiku_workshop_id' => null,

                        ]

                    );
                DB::connection('aurora')->table('Supplier Part Historic Dimension')
                    ->update(
                        [
                            'aiku_supplier_historic_product_id' => null,
                            'aiku_workshop_historic_product_id' => null
                        ]
                    );
                $this->line("✅ supplier products \t".$this->stepTime());
                DB::connection('aurora')->table('Inventory Transaction Fact')
                    ->update([
                        'aiku_id' => null,
                        'aiku_dn_item_id' => null,
                        'aiku_picking_id' => null,

                             ]);

                $this->line("✅ stock movements \t".$this->stepTime());

                DB::connection('aurora')->table('Product Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Product History Dimension')
                    ->update(['aiku_id' => null]);

                $this->line("✅ products \t\t".$this->stepTime());

                DB::connection('aurora')->table('Customer Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Customer Deleted Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Customer Client Dimension')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Customer Favourite Product Fact')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Back in Stock Reminder Fact')
                    ->update(['aiku_id' => null]);
                DB::connection('aurora')->table('Customer Portfolio Fact')
                    ->update(['aiku_id' => null]);

                $this->line("✅ customers \t\t".$this->stepTime());


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

                $this->line("✅ orders \t\t".$this->stepTime());




            }
        });


        return 0;
    }

    function stepTime(){
        $roolTime = microtime(true) - $this->timeStart;
        $diff = microtime(true) - $this->timeLastStep;
        $this->timeLastStep=microtime(true);

        return "\t".round($roolTime, 2).'s' . "\t\t".round($diff, 2).'s';
    }

}
