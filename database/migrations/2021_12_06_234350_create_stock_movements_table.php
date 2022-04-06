<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Dec 2021 15:57:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->id();

                $table->enum('type', ['purchase', 'return', 'delivery', 'lost', 'found', 'location-transfer', 'cancelled-to-restock', 'cancelled-restocked', 'amendment', 'consumption'])->index();

                $table->morphs('stockable');

                //$table->unsignedBigInteger('stock_id')->nullable()->index();
                //$table->foreign('stock_id')->references('id')->on('stocks');

                $table->unsignedMediumInteger('location_id')->nullable()->index();
                $table->foreign('location_id')->references('id')->on('locations');

                $table->decimal('quantity', 16, 3);
                $table->decimal('amount', 16, 3);

                $table->jsonb('data');

                $table->timestampsTz();
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
        Schema::dropIfExists('stock_movements');
    }
}
