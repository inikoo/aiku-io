<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 02:32:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniqueStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::create('unique_stocks', function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default('true')->index();
                $table->string('reference')->nullable()->index();

                $table->enum('state', ['in-process', 'received', 'booked-in', 'booked-out', 'invoiced', 'lost'])->default('in-process')->index();
                $table->enum('type', ['pallet', 'box', 'oversize', 'item'])->default('item')->index();

                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');

                $table->unsignedBigInteger('location_id')->nullable();
                $table->foreign('location_id')->references('id')->on('locations');
                $table->text('notes')->nullable();
                $table->dateTimeTz('delivered_at')->nullable();
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
        Schema::dropIfExists('unique_stocks');
    }
}
