<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 12 Nov 2021 01:37:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {


            $table->id();

            $table->unsignedMediumInteger('shop_id')->nullable()->index();
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->unsignedBigInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->string('nickname')->nullable()->index();
            $table->enum('state',['in-basket','in-process','in-warehouse','packed','packed-done','cancelled'])->default('in-basket')->index();

            $table->unsignedBigInteger('items')->default(0)->comment('number of items');

            $table->decimal('items_discounts', 16)->default(0);
            $table->decimal('items_net', 16)->default(0);

            $table->unsignedSmallInteger('currency_id');

            $table->decimal('charges', 16)->default(0);
            $table->decimal('shipping', 16)->default(null)->nullable();
            $table->decimal('net', 16)->default(0);
            $table->decimal('tax', 16)->default(0);

            $table->jsonb('data');

            $table->timestampsTz();
            $table->unsignedBigInteger('aurora_id')->nullable();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baskets');
    }
}
