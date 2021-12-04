<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 01 Dec 2021 15:16:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedBigInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('order_id')->nullable()->constrained();

            $table->foreignId('transaction_id')->nullable()->constrained();


            $table->nullableMorphs('item');


            $table->decimal('quantity', 16, 3);
            $table->decimal('net', 16)->default(0);
            $table->decimal('discounts', 16)->default(0);

            $table->decimal('tax', 16)->default(0);
            $table->unsignedMediumInteger('tax_band_id')->nullable()->index();
            $table->jsonb('data');

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unsignedBigInteger('aurora_id')->nullable()->index();
            $table->unsignedBigInteger('aurora_no_product_id')->nullable()->index();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_transactions');
    }
}
