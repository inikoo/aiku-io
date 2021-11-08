<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 07 Nov 2021 17:19:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('location_stock', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->unsignedMediumInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');

            $table->decimal('quantity', 16, 3);
            $table->smallInteger('picking_priority')->default(0)->index();

            $table->string('notes')->nullable();

            $table->jsonb('data');
            $table->jsonb('settings');

            $table->dateTimeTz('audited_at')->nullable()->index();
            $table->timestampsTz();
            $table->unsignedBigInteger('aurora_part_id')->nullable();
            $table->unsignedBigInteger('aurora_location_id')->nullable();

            $table->unique(['stock_id','location_id']);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_stock');
    }
}
