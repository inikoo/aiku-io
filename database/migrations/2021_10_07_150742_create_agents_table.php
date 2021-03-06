<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 08 Oct 2021 17:52:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::create('agents', function (Blueprint $table) {
                $table->mediumIncrements('id');
                $table->boolean('status')->default(true)->index();
                $table->string('code')->index();
                $table->morphs('owner');
                $table->string('name');
                $table->string('company_name', 256)->nullable();
                $table->string('contact_name', 256)->nullable()->index();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->unsignedBigInteger('address_id')->nullable()->index();
                $table->foreign('address_id')->references('id')->on('addresses');
                $table->jsonb('location');

                $table->unsignedSmallInteger('currency_id');
                //$table->foreign('currency_id')->references('id')->on('aiku.currencies');

                $table->jsonb('settings');
                $table->jsonb('data');
                $table->timestampsTz();
                $table->softDeletesTz();
                $table->unsignedBigInteger('aurora_id');
            });

            Schema::create('agent_stats', function (Blueprint $table) {
                $table->mediumIncrements('id');
                $table->unsignedMediumInteger('agent_id')->index();
                $table->foreign('agent_id')->references('id')->on('agents');
                $table->unsignedSmallInteger('number_suppliers')->default(0);
                $table->unsignedMediumInteger('number_products')->default(0);
                $table->unsignedMediumInteger('number_purchase_orders')->default(0);
                $purchaseOrderStates = ['in-process', 'submitted', 'confirmed', 'dispatched', 'delivered', 'cancelled'];
                foreach ($purchaseOrderStates as $purchaseOrderState) {
                    $table->unsignedBigInteger('number_purchase_orders_state_'.str_replace('-', '_', $purchaseOrderState))->default(0);
                }

                $table->unsignedSmallInteger('number_deliveries')->default(0);

                $table->timestampsTz();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_stats');
        Schema::dropIfExists('agents');
    }
}
