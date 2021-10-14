<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            //$table->unsignedMediumInteger('shop_id')->index()->nullable();
            //$table->foreign('shop_id')->references('id')->on('shops');

            $table->morphs('vendor');

            $table->string('name', 256)->nullable();
            $table->enum('status',['pending-approval','approved','rejected','banned'])->index();
            $table->enum('state',['in-process','active','losing','lost'])->index()->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable()->index();
            $table->foreign('billing_address_id')->references('id')->on('addresses');
            $table->unsignedBigInteger('delivery_address_id')->nullable()->index();
            $table->foreign('delivery_address_id')->references('id')->on('addresses');


            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unsignedBigInteger('aurora_customer_id')->nullable()->unique();
            $table->unsignedBigInteger('aurora_customer_client_id')->nullable()->unique();

            $table->index([DB::raw('name(64)')]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
