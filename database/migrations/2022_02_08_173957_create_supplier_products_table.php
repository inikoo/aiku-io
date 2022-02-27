<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 01:43:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierProductsTable extends Migration
{

    public function up()
    {
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
            $table->enum('composition', ['unit', 'multiple', 'mix'])->default('unit');

            $table->string('slug')->nullable()->index();


            $table->unsignedMediumInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->unsignedMediumInteger('agent_id')->nullable();
            $table->foreign('agent_id')->references('id')->on('agents');


            $table->enum('state', ['creating', 'active', 'no-available', 'discontinuing', 'discontinued'])->nullable()->index();
            $table->boolean('status')->nullable()->index();

            $table->string('code')->index();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();

            $table->unsignedDecimal('cost', 18,4)->comment('unit cost');
            $table->unsignedMediumInteger('pack')->nullable()->comment('units per pack');
            $table->unsignedMediumInteger('outer')->nullable()->comment('units per outer');
            $table->unsignedMediumInteger('carton')->nullable()->comment('units per carton');

            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('images');
            $table->jsonb('settings');
            $table->jsonb('data');

            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('historic_supplier_products', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->index();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('deleted_at')->nullable();
            $table->unsignedBigInteger('supplier_product_id')->nullable()->index();
            $table->foreign('supplier_product_id')->references('id')->on('supplier_products');
            $table->unsignedDecimal('cost',  18,4)->comment('unit cost');
            $table->string('code')->nullable();
            $table->string('name',255)->nullable();
            $table->unsignedMediumInteger('pack')->nullable()->comment('units per pack');
            $table->unsignedMediumInteger('outer')->nullable()->comment('units per outer');
            $table->unsignedMediumInteger('carton')->nullable()->comment('units per carton');
            $table->unsignedDecimal('cbm',  18,4)->nullable()->comment('to be deleted');
            $table->unsignedSmallInteger('currency_id')->nullable();
            //$table->foreign('currency_id')->references('id')->on('aiku.currencies');
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create(
            'supplier_product_trade_unit',
            function (Blueprint $table) {
                $table->unsignedBigInteger('supplier_product_id')->nullable();
                $table->foreign('supplier_product_id')->references('id')->on('supplier_products');
                $table->unsignedBigInteger('trade_unit_id')->nullable();
                $table->foreign('trade_unit_id')->references('id')->on('trade_units');
                $table->decimal('quantity', 12, 3);
                $table->string('notes')->nullable();

                $table->timestampsTz();
            }
        );
    }


    public function down()
    {
        Schema::dropIfExists('supplier_product_trade_unit');
        Schema::dropIfExists('historic_supplier_products');
        Schema::dropIfExists('supplier_products');
    }
}
