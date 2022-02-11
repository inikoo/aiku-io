<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 23:38:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{

    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('company_name', 256)->nullable();
            $table->string('contact_name', 256)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website', 256)->nullable();
            $table->string('tax_number')->nullable()->index();
            $table->enum('tax_number_status', ['valid', 'invalid', 'na', 'unknown'])->nullable()->default('na');
            $table->string('identity_document_type')->nullable();
            $table->string('identity_document_number')->nullable();
            $table->unsignedBigInteger('address_id')->nullable()->index();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->jsonb('location');


            $table->enum('state', ['in-process', 'open', 'closing-down', 'closed'])->index();
            $table->enum('type', ['shop', 'fulfilment_house'])->index();
            $table->enum('subtype', ['b2b', 'b2c', 'storage', 'fulfilment', 'dropshipping'])->nullable();

            $table->date('open_at')->nullable();
            $table->date('closed_at')->nullable();
            $table->unsignedSmallInteger('language_id');
            //$table->foreign('language_id')->references('id')->on('aiku.languages');
            $table->unsignedSmallInteger('currency_id');
            //$table->foreign('currency_id')->references('id')->on('aiku.currencies');
            $table->unsignedSmallInteger('timezone_id');
            //$table->foreign('timezone_id')->references('id')->on('aiku.timezones');
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('shop_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedBigInteger('number_customers')->default(0);
            $customerStates = ['in-process','active','losing','lost','registered'];
            foreach ($customerStates as $customerState) {
                $table->unsignedBigInteger('number_customers_state_'.str_replace('-', '_', $customerState))->default(0);
            }
            $customerNumberInvoicesStates = ['none','one','many'];
            foreach ($customerNumberInvoicesStates as $customerNumberInvoicesState) {
                $table->unsignedBigInteger('number_customers_trade_state_'.$customerNumberInvoicesState)->default(0);
            }

            $table->unsignedBigInteger('number_products')->default(0);
            $productStates = ['creating', 'active', 'no-available', 'discontinuing', 'discontinued'];
            foreach ($productStates as $productState) {
                $table->unsignedBigInteger('number_products_state_'.str_replace('-', '_', $productState))->default(0);
            }
            $table->unsignedBigInteger('number_families')->default(0);
            $table->unsignedBigInteger('number_departments')->default(0);


            $table->unsignedBigInteger('number_orders')->default(0);
            $orderStates = ['in-basket', 'in-process', 'in-warehouse', 'packed', 'packed-done', 'dispatched', 'returned', 'cancelled'];
            foreach ($orderStates as $orderState) {
                $table->unsignedBigInteger('number_orders_state_'.str_replace('-', '_', $orderState))->default(0);
            }

            $table->unsignedBigInteger('number_deliveries')->default(0);
            $table->unsignedBigInteger('number_deliveries_type_order')->default(0);
            $table->unsignedBigInteger('number_deliveries_type_replacement')->default(0);

            $deliveryStates=[
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


            $table->unsignedBigInteger('number_invoices')->default(0);
            $table->unsignedBigInteger('number_invoices_type_invoice')->default(0);
            $table->unsignedBigInteger('number_invoices_type_refund')->default(0);







            $table->timestampsTz();
        });
    }


    public function down()
    {
        Schema::dropIfExists('shop_stats');
        Schema::dropIfExists('shops');
    }
}
