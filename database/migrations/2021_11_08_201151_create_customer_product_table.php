<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 09 Nov 2021 14:43:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::create('favourites', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products');
                $table->timestampsTz();
                $table->unsignedBigInteger('aurora_id')->nullable();
                $table->unique(['customer_id', 'product_id']);
            });

            Schema::create('back_to_stock_reminders', function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default('true')->index()->comment('true when standby|ready ');
                $table->enum('state', ['standby', 'ready', 'deleted', 'send'])->default('standby')->index();

                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products');
                $table->timestampsTz();
                $table->dateTimeTz('send_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();

                $table->unsignedBigInteger('aurora_id')->nullable();
            });

            Schema::create('portfolio', function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default('true')->index();

                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products');
                $table->string('customer_reference')->nullable();
                $table->jsonb('data');
                $table->jsonb('settings');
                $table->timestampsTz();
                $table->dateTimeTz('removed_at')->nullable();
                $table->unsignedBigInteger('aurora_id')->nullable();
                $table->unique(['customer_id', 'product_id']);
            });


            Schema::create('customised_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products');


                $table->timestampsTz();

                $table->unsignedBigInteger('aurora_id')->nullable();
                $table->unique(['customer_id', 'product_id']);
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
        Schema::dropIfExists('customised_products');
        Schema::dropIfExists('portfolio');
        Schema::dropIfExists('back_to_stock_reminders');
        Schema::dropIfExists('favourites');

    }
}
