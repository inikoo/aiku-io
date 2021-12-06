<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 05 Dec 2021 15:16:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();

            $table->unsignedMediumInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedBigInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBigInteger('order_id')->index()->comment('Main order, usually the only one (used for performance)');
            $table->foreign('order_id')->references('id')->on('orders');


            $table->string('number')->index();
            $table->enum('type',['order','replacement'])->default('order')->index();

            $table->enum('state',
                         [
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


                         ]
            )->index();
            //$table->string('status')->nullable()->index();


            $table->unsignedMediumInteger('delivery_address_id')->nullable()->index();
            $table->foreign('delivery_address_id')->references('id')->on('addresses');
            $table->unsignedMediumInteger('shipper_id')->nullable()->index();
            $table->foreign('shipper_id')->references('id')->on('shippers');

            $table->decimal('weight', 16)->nullable()->default(0);
            $table->unsignedMediumInteger('number_stocks')->default(0);
            $table->unsignedMediumInteger('number_picks')->default(0);


            $table->unsignedMediumInteger('picker_id')->nullable()->index()->comment('Main picker');
            $table->foreign('picker_id')->references('id')->on('employees');
            $table->unsignedMediumInteger('packer_id')->nullable()->index()->comment('Main packer');
            $table->foreign('packer_id')->references('id')->on('employees');

            $table->dateTimeTz('date')->index();

            $table->dateTimeTz('order_submitted_at')->nullable();

            $table->dateTimeTz('assigned_at')->nullable();
            $table->dateTimeTz('picking_at')->nullable();
            $table->dateTimeTz('picked_at')->nullable();
            $table->dateTimeTz('packing_at')->nullable();
            $table->dateTimeTz('packed_at')->nullable();
            $table->dateTimeTz('dispatched_at')->nullable();
            $table->dateTimeTz('cancelled_at')->nullable();


            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_notes');
    }
}
