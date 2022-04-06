<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::create('customer_clients', function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->index();
                $table->unsignedMediumInteger('shop_id')->index()->nullable();
                $table->foreign('shop_id')->references('id')->on('shops');
                $table->unsignedMediumInteger('customer_id')->index()->nullable();
                $table->foreign('customer_id')->references('id')->on('customers');


                $table->string('name', 256)->nullable();

                $table->string('contact_name', 256)->nullable()->index();
                $table->string('company_name', 256)->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->jsonb('location');


                $table->unsignedBigInteger('delivery_address_id')->nullable()->index();
                $table->foreign('delivery_address_id')->references('id')->on('addresses');


                $table->dateTimeTz('deactivated_at')->nullable();
                $table->timestampsTz();
                $table->softDeletesTz();

                $table->unsignedBigInteger('aurora_id')->nullable()->unique();

                $table->index([DB::raw('name(64)')]);
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
        Schema::dropIfExists('customer_clients');
    }
}
