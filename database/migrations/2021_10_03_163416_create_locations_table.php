<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 00:53:14 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('warehouse_id')->index();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');

            $table->unsignedSmallInteger('warehouse_area_id')->nullable()->index();
            $table->foreign('warehouse_area_id')->references('id')->on('warehouse_areas');

            $table->enum('state', ['operational', 'broken', 'deleted'])->index()->default('operational');
            $table->string('code', 64)->index();
            $table->boolean('is_empty')->default(true);

            $table->jsonb('data');



            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('location_stats', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('location_id')->index();
            $table->foreign('location_id')->references('id')->on('locations');

            $table->unsignedSmallInteger('number_stock_slots')->default(0);
            $table->unsignedSmallInteger('number_empty_stock_slots')->default(0);
            $table->decimal('stock_value',16)->default(0);
            $table->timestampsTz();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_stats');
        Schema::dropIfExists('locations');
    }
}
