<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 02:17:57 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFulfilmentCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::create('customer_fulfilment_stats', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->index()->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
                $table->unsignedSmallInteger('number_unique_stocks')->default(0);
                $uniqueStockTypes = ['pallet', 'box', 'oversize', 'item'];
                foreach ($uniqueStockTypes as $uniqueStockType) {
                    $table->unsignedBigInteger('number_unique_stocks_type'.str_replace('-', '_', $uniqueStockType))->default(0);
                }
                $uniqueStockStates = ['in-process', 'received', 'booked-in', 'booked-out', 'invoiced', 'lost'];
                foreach ($uniqueStockStates as $uniqueStockState) {
                    $table->unsignedBigInteger('number_unique_stocks_state_'.str_replace('-', '_', $uniqueStockState))->default(0);
                }
                $table->timestampsTz();
                $table->softDeletesTz();
                $table->unsignedBigInteger('aurora_id')->nullable()->unique();
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
        Schema::dropIfExists('customer_fulfilment_stats');
    }
}
