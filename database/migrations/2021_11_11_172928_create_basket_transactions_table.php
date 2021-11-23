<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basket_transactions', function (Blueprint $table) {


            $table->id();
            $table->unsignedBigInteger('basket_id')->index();
            $table->foreign('basket_id')->references('id')->on('baskets');

            $table->nullableMorphs('item');




            $table->decimal('quantity', 16, 3);

            $table->decimal('discounts', 16)->default(0);
            $table->decimal('net', 16)->default(0);

            $table->unsignedBigInteger('tax_band_id')->nullable()->index();
            $table->foreign('tax_band_id')->references('id')->on('aiku.tax_bands');

            $table->jsonb('data');

            $table->timestampsTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basket_transactions');
    }
}
