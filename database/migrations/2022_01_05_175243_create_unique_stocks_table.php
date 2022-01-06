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
        Schema::create('unique_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->index();

            $table->enum('state', ['in-process', 'received', 'booked-in', 'booked-out', 'invoiced', 'lost'])->index();
            $table->enum('type', ['pallet', 'box', 'oversize'])->index();

            $table->unsignedBigInteger('fulfilment_customer_id')->nullable();
            $table->foreign('fulfilment_customer_id')->references('id')->on('fulfilment_customers');

            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->text('notes');

            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });
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
