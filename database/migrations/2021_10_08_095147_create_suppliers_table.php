<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->boolean('status')->default(true)->index();
            $table->string('code')->index();
            $table->morphs('owner');
            $table->string('name');
            $table->string('company_name',256)->nullable();
            $table->string('contact_name',256)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('address_id')->nullable()->index();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->jsonb('location');

            $table->unsignedSmallInteger('currency_id');
            //$table->foreign('currency_id')->references('id')->on('aiku.currencies');
            $table->jsonb('settings');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id');
        });

        Schema::create('supplier_stats', function (Blueprint $table) {

            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('supplier_id')->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->unsignedSmallInteger('number_products')->default(0);


            $productStates = ['creating', 'active', 'no-available', 'discontinuing', 'discontinued'];
            foreach ($productStates as $productState) {
                $table->unsignedBigInteger('number_products_state_'.str_replace('-', '_', $productState))->default(0);
            }

            $productStockQuantityStatuses = ['surplus','optimal','low','critical','out-of-stock','no_applicable'];
            foreach ($productStockQuantityStatuses as $productStockQuantityStatus) {
                $table->unsignedBigInteger('number_products_stock_quantity_status_'.str_replace('-', '_', $productStockQuantityStatus))->default(0);
            }

            $table->unsignedSmallInteger('number_purchase_orders')->default(0);
            $purchaseOrderStates = ['in-process', 'submitted',  'confirmed', 'dispatched', 'delivered','cancelled'];
            foreach ($purchaseOrderStates as $purchaseOrderState) {
                $table->unsignedBigInteger('number_purchase_orders_state_'.str_replace('-', '_', $purchaseOrderState))->default(0);
            }

            $table->unsignedSmallInteger('number_deliveries')->default(0);



            $table->timestampsTz();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_stats');
        Schema::dropIfExists('suppliers');
    }
}
