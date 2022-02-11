<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 29 Oct 2021 11:56:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->morphs('owner');
            $table->enum('composition', ['unit', 'multiple', 'mix'])->default('unit');
            $table->enum('state',['in-process','active','discontinuing','discontinued'])->nullable()->index();
            $table->enum('quantity_status',['surplus','optimal','low','critical','out-of-stock','error'])->nullable()->index();
            $table->boolean('sellable')->default(1)->index();
            $table->boolean('raw_material')->default(0)->index();
            $table->string('slug')->index();
            $table->string('code')->index();
            $table->string('barcode')->index()->nullable();
            $table->text('description')->nullable();
            $table->unsignedMediumInteger('pack')->nullable()->comment('units per pack');
            $table->unsignedMediumInteger('outer')->nullable()->comment('units per outer');
            $table->unsignedMediumInteger('carton')->nullable()->comment('units per carton');
            $table->decimal('quantity', 16, 3)->nullable()->comment('stock quantity in units');
            $table->float('available_forecast')->nullable()->comment('days');
            $table->decimal('value', 16)->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('images');
            $table->unsignedBigInteger('package_image_id')->nullable();
            $table->foreign('package_image_id')->references('id')->on('images');
            $table->jsonb('settings');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create(
            'stock_trade_unit',
            function (Blueprint $table) {
                $table->unsignedBigInteger('stock_id')->nullable();
                $table->foreign('stock_id')->references('id')->on('stocks');
                $table->unsignedBigInteger('trade_unit_id')->nullable();
                $table->foreign('trade_unit_id')->references('id')->on('trade_units');
                $table->decimal('quantity', 12, 3);

                $table->timestampsTz();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_trade_unit');
        Schema::dropIfExists('stocks');
    }
}
