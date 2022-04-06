<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 01:54:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::create('workshop_products', function (Blueprint $table) {
                $table->id();
                $table->enum('composition', ['unit', 'multiple', 'mix'])->default('unit');

                $table->string('slug')->nullable()->index();


                $table->unsignedMediumInteger('workshop_id')->nullable();
                $table->foreign('workshop_id')->references('id')->on('workshops');


                $table->enum('state', ['creating', 'active', 'no-available', 'discontinuing', 'discontinued'])->nullable()->index();
                $table->boolean('status')->nullable()->index();

                $table->string('code')->index();
                $table->string('name', 255)->nullable();
                $table->text('description')->nullable();

                $table->unsignedDecimal('cost', 18)->comment('unit cost');
                $table->unsignedMediumInteger('pack')->nullable()->comment('units per pack');
                $table->unsignedMediumInteger('outer')->nullable()->comment('units per outer');
                $table->unsignedMediumInteger('carton')->nullable()->comment('units per carton');
                $table->unsignedMediumInteger('batch')->nullable()->comment('units per batch');

                $table->unsignedBigInteger('image_id')->nullable();
                $table->foreign('image_id')->references('id')->on('images');
                $table->jsonb('settings');
                $table->jsonb('data');

                $table->timestampsTz();
                $table->softDeletesTz();
                $table->unsignedBigInteger('aurora_id')->nullable()->unique();
            });

            Schema::create('historic_workshop_products', function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->index();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unsignedBigInteger('workshop_product_id')->nullable()->index();
                $table->foreign('workshop_product_id')->references('id')->on('workshop_products');
                $table->string('code')->nullable();
                $table->string('name', 255)->nullable();
                $table->unsignedMediumInteger('pack')->nullable()->comment('units per pack');
                $table->unsignedMediumInteger('outer')->nullable()->comment('units per outer');
                $table->unsignedMediumInteger('carton')->nullable()->comment('units per carton');
                $table->unsignedMediumInteger('batch')->nullable()->comment('units per batch');

                //$table->foreign('currency_id')->references('id')->on('aiku.currencies');
                $table->unsignedBigInteger('aurora_id')->nullable()->unique();
            });

            Schema::create(
                'trade_unit_workshop_product',
                function (Blueprint $table) {
                    $table->unsignedBigInteger('workshop_product_id')->nullable();
                    $table->foreign('workshop_product_id')->references('id')->on('workshop_products');
                    $table->unsignedBigInteger('trade_unit_id')->nullable();
                    $table->foreign('trade_unit_id')->references('id')->on('trade_units');
                    $table->decimal('quantity', 12, 3);
                    $table->string('notes')->nullable();

                    $table->timestampsTz();
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_unit_workshop_product');
        Schema::dropIfExists('historic_workshop_products');
        Schema::dropIfExists('workshop_products');
    }
}
