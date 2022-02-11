<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 23:02:11 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');

            $table->unsignedSmallInteger('number_employees')->default(0);

            $employeeStates = ['hired', 'working', 'left'];
            foreach ($employeeStates as $employeeState) {
                $table->unsignedSmallInteger('number_employees_state_'.$employeeState)->default(0);
            }

            $table->unsignedSmallInteger('number_guests')->default(0);

            $table->unsignedSmallInteger('number_users')->default(0);
            $table->unsignedSmallInteger('number_users_status_active')->default(0);
            $table->unsignedSmallInteger('number_users_status_inactive')->default(0);

            $userTypes = ['tenant', 'employee', 'guest', 'supplier', 'agent', 'customer'];
            foreach ($userTypes as $userType) {
                $table->unsignedSmallInteger('number_users_type_'.$userType)->default(0);
            }

            $table->unsignedSmallInteger('number_images')->default(0);
            $table->unsignedBigInteger('filesize_images')->default(0);
            $table->unsignedSmallInteger('number_attachments')->default(0);
            $table->unsignedBigInteger('filesize_attachments')->default(0);


            $table->timestampsTz();
        });

        Schema::create('tenant_trade_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');

            $table->unsignedSmallInteger('number_shops')->default(0);

            $shopTypes = ['shop', 'fulfilment_house'];
            foreach ($shopTypes as $shopType) {
                $table->unsignedSmallInteger('number_shops_type_'.$shopType)->default(0);
            }
            $userSubtypes = ['b2b', 'b2c', 'storage', 'fulfilment', 'dropshipping'];
            foreach ($userSubtypes as $userSubtype) {
                $table->unsignedSmallInteger('number_shops_subtype_'.$userSubtype)->default(0);
            }

            $table->unsignedBigInteger('number_customers')->default(0);
            $customerStates = ['in-process', 'active', 'losing', 'lost', 'registered'];
            foreach ($customerStates as $customerState) {
                $table->unsignedBigInteger('number_customers_state_'.str_replace('-', '_', $customerState))->default(0);
            }
            $customerNumberInvoicesStates = ['none', 'one', 'many'];
            foreach ($customerNumberInvoicesStates as $customerNumberInvoicesState) {
                $table->unsignedBigInteger('number_customers_trade_state_'.$customerNumberInvoicesState)->default(0);
            }


            $table->unsignedBigInteger('number_orders')->default(0);
            $orderStates = ['in-basket', 'in-process', 'in-warehouse', 'packed', 'packed-done', 'dispatched', 'returned', 'cancelled'];
            foreach ($orderStates as $orderState) {
                $table->unsignedBigInteger('number_orders_state_'.str_replace('-', '_', $orderState))->default(0);
            }


            $table->unsignedBigInteger('number_invoices')->default(0);
            $table->unsignedBigInteger('number_invoices_type_invoice')->default(0);
            $table->unsignedBigInteger('number_invoices_type_refund')->default(0);


            $table->timestampsTz();
        });

        Schema::create('tenant_inventory_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');


            $table->unsignedSmallInteger('number_warehouses')->default(0);

            $table->unsignedSmallInteger('number_warehouse_areas')->default(0);

            $table->unsignedMediumInteger('number_locations')->default(0);
            $table->unsignedMediumInteger('number_locations_state_operational')->default(0);
            $table->unsignedMediumInteger('number_locations_state_broken')->default(0);



            $table->unsignedBigInteger('number_stocks')->default(0);
            $stockStates = ['in-process','active','discontinuing','discontinued'];
            foreach ($stockStates as $stockState) {
                $table->unsignedBigInteger('number_stocks_state_'.str_replace('-', '_', $stockState))->default(0);
            }
            $stockQuantityStatuses = ['surplus','optimal','low','critical','out-of-stock','error'];
            foreach ($stockQuantityStatuses as $stockQuantityStatus) {
                $table->unsignedBigInteger('number_stocks_quantity_status_'.str_replace('-', '_', $stockQuantityStatus))->default(0);
            }


            $table->unsignedBigInteger('number_deliveries')->default(0);
            $table->unsignedBigInteger('number_deliveries_type_order')->default(0);
            $table->unsignedBigInteger('number_deliveries_type_replacement')->default(0);

            $deliveryStates = [
                'ready-to-be-picked',
                'picker-assigned',
                'picking',
                'picked',
                'packing',
                'packed',
                'packed-done',
                'approved',
                'dispatched',
                'cancelled',
                'cancelled-to-restock',
            ];

            foreach ($deliveryStates as $deliveryState) {
                $table->unsignedBigInteger('number_deliveries_state_'.str_replace('-', '_', $deliveryState))->default(0);
            }


            $table->timestampsTz();
        });

        Schema::create('tenant_procurement_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');

            $table->unsignedMediumInteger('number_suppliers')->default(0);
            $table->unsignedMediumInteger('number_suppliers_owner_type_tenant')->default(0);
            $table->unsignedMediumInteger('number_suppliers_owner_type_agent')->default(0);
            $table->unsignedMediumInteger('number_suppliers_owner_type_other_tenant')->default(0);

            $table->unsignedMediumInteger('number_suppliers_status_active')->default(0);
            $table->unsignedMediumInteger('number_suppliers_status_inactive')->default(0);


            $table->unsignedMediumInteger('number_agents')->default(0);
            $table->unsignedMediumInteger('number_agents_owner_type_tenant')->default(0);
            $table->unsignedMediumInteger('number_agents_owner_type_other_tenant')->default(0);


            $table->unsignedBigInteger('number_purchase_orders')->default(0);
            $purchaseOrderStates = ['in-process', 'submitted', 'no-received', 'confirmed', 'manufactured', 'qc-pass', 'inputted', 'dispatched', 'received', 'checked', 'placed', 'costing', 'invoice-checked', 'cancelled'];
            foreach ($purchaseOrderStates as $purchaseOrderState) {
                $table->unsignedBigInteger('number_purchase_orders_state_'.str_replace('-', '_', $purchaseOrderState))->default(0);
            }

            $table->unsignedBigInteger('number_deliveries')->default(0);

            $table->unsignedSmallInteger('number_workshops')->default(0);


            $table->timestampsTz();
        });

        Schema::create('tenant_sales_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->unsignedSmallInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');


            $periods           = ['all', '1y', '1q', '1m', ',1w', 'ytd', 'qtd', 'mtd', 'wtd', 'lm', 'lw', 'yda', 'tdy'];
            $periods_last_year = ['1y', '1q', '1m', ',1w', 'ytd', 'qtd', 'mtd', 'wtd', 'lm', 'lw', 'yda', 'tdy'];
            $previous_years    = ['py1', 'py2', 'py3', 'py4', 'py5'];
            $previous_quarters = ['pq1', 'pq2', 'pq3', 'pq4', 'pq5'];

            foreach ($periods as $col) {
                $table->decimal($col)->default(0);
            }
            foreach ($periods_last_year as $col) {
                $table->decimal($col.'_ly')->default(0);
            }
            foreach ($previous_years as $col) {
                $table->decimal($col)->default(0);
            }
            foreach ($previous_quarters as $col) {
                $table->decimal($col)->default(0);
            }


            $table->timestamps();
            $table->unique(['tenant_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_sales_stats');
        Schema::dropIfExists('tenant_procurement_stats');
        Schema::dropIfExists('tenant_inventory_stats');
        Schema::dropIfExists('tenant_trade_stats');
        Schema::dropIfExists('tenant_stats');
    }
}
