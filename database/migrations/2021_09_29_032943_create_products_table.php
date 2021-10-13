<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 11:30:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{



    public function up()
    {
        Schema::create(
            'products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->index();


            $table->morphs('vendor');




            $table->enum('state',['creating','active','no-available','discontinuing','discontinued'])->nullable()->index();
            $table->boolean('status')->nullable()->index();

            $table->string('code')->index();
            $table->string('name',255)->nullable();
            $table->text('description')->nullable();

            $table->unsignedDecimal('price',  18,4)->comment('unit price');
            $table->unsignedMediumInteger('pack')->nullable()->comment('units per pack');
            $table->unsignedMediumInteger('outer')->nullable()->comment('units per outer');
            $table->unsignedMediumInteger('carton')->nullable()->comment('units per carton');

            $table->unsignedMediumInteger('available')->default(0)->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('images');
            $table->jsonb('settings');
            $table->jsonb('data');

            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_product_id')->nullable()->unique();
            $table->unsignedBigInteger('aurora_supplier_product_id')->nullable()->unique();


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
        Schema::dropIfExists('products');
    }
}
