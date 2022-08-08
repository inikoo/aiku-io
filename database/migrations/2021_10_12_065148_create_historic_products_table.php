<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('historic_products', function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->index();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unsignedBigInteger('product_id')->nullable()->index();
                $table->foreign('product_id')->references('id')->on('products');
                $table->unsignedDecimal('price', 18)->comment('unit price');
                $table->string('code')->nullable();
                $table->string('name', 255)->nullable();
                $table->unsignedDecimal('pack',8,3)->nullable()->comment('units per pack');
                $table->unsignedDecimal('outer',8,3)->nullable()->comment('units per outer');
                $table->unsignedDecimal('carton',8,3)->nullable()->comment('units per carton');

                $table->unsignedDecimal('cbm', 18, 4)->nullable()->comment('to be deleted');
                $table->unsignedSmallInteger('currency_id')->nullable();
                //$table->foreign('currency_id')->references('id')->on('aiku.currencies');
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
        Schema::dropIfExists('historic_products');
    }
}
