<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 16:09:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickings', function (Blueprint $table) {
            $table->id();

            $table->boolean('fulfilled')->default(false)->index();

            $table->enum('state',['created','assigned','picking','queried','waiting','picked','packing','done'])->index()->default('created');
            $table->enum('status',['processing','packed','partially_packed','out_of_stock','cancelled'])->index()->default('processing');


            $table->unsignedBigInteger('delivery_note_id')->nullable()->index();
            $table->foreign('delivery_note_id')->references('id')->on('delivery_notes');


            $table->unsignedBigInteger('stock_id')->nullable()->index();
            $table->foreign('stock_id')->references('id')->on('stocks');

            $table->unsignedBigInteger('picked_by')->nullable()->index();
            $table->foreign('picked_by')->references('id')->on('employees');

            $table->unsignedBigInteger('packed_by')->nullable()->index();
            $table->foreign('packed_by')->references('id')->on('employees');

            $table->decimal('required', 16, 3);
            $table->decimal('picked', 16, 3)->nullable();
            $table->decimal('weight', 16, 3)->nullable();

            $table->jsonb('data');


            $table->dateTimeTz('assigned_at', 0)->nullable();
            $table->dateTimeTz('picking_at', 0)->nullable();
            $table->dateTimeTz('picked_at', 0)->nullable();
            $table->dateTimeTz('packing_at', 0)->nullable();
            $table->dateTimeTz('packed_at', 0)->nullable();


            $table->timestampsTz();

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
        Schema::dropIfExists('pickings');
    }
}
