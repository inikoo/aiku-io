<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 04 Oct 2021 00:53:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('warehouse_areas', function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedSmallInteger('warehouse_id');
                $table->foreign('warehouse_id')->references('id')->on('warehouses');
                $table->string('code')->index();
                $table->string('name');
                $table->timestampsTz();
                $table->softDeletesTz();
                $table->unsignedBigInteger('aurora_id')->nullable()->unique();
            });

            Schema::create('warehouse_area_stats', function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedSmallInteger('warehouse_area_id')->index();
                $table->foreign('warehouse_area_id')->references('id')->on('warehouse_areas');

                $table->unsignedSmallInteger('number_locations')->default(0);
                $table->unsignedSmallInteger('number_empty_locations')->default(0);
                $table->decimal('stock_value', 16)->default(0);
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
        Schema::dropIfExists('warehouse_area_stats');
        Schema::dropIfExists('warehouse_areas');
    }
}
