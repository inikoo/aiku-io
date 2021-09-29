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
            $table->increments('id');
            $table->string('slug')->nullable()->index();

            $table->unsignedMediumInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');


            $table->string('state')->nullable()->index();
            $table->boolean('status')->nullable()->index();

            $table->string('code')->index();
            $table->text('name')->nullable();
            $table->text('description')->nullable();

            $table->decimal('unit_price');
            $table->unsignedMediumInteger('units');

            $table->unsignedMediumInteger('available')->default(0)->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('images');
            $table->jsonb('settings');
            $table->jsonb('data');

            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedMediumInteger('aurora_id')->nullable()->unique();


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
