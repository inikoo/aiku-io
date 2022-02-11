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
            $table->unsignedMediumInteger('shop_id')->index()->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');


            $table->string('name', 256)->nullable();
            $table->string('contact_name',256)->nullable()->index();
            $table->string('company_name',256)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('identity_document_number')->nullable();
            $table->string('website',256)->nullable();
            $table->string('tax_number')->nullable()->index();
            $table->enum('tax_number_status', ['valid', 'invalid', 'na', 'unknown'])->nullable()->default('na');
            $table->jsonb('tax_number_data');
            $table->jsonb('location');

            $table->enum('status',['pending-approval','approved','rejected','banned'])->index();
            $table->enum('state',['in-process','active','losing','lost','registered'])->index()->nullable();
            $table->enum('trade_state',['none','one','many'])->index()->nullable()->default('none')->comment('number of invoices');

            $table->unsignedBigInteger('billing_address_id')->nullable()->index();
            $table->foreign('billing_address_id')->references('id')->on('addresses');
            $table->unsignedBigInteger('delivery_address_id')->nullable()->index()->comment('null for customers in fulfilment shops');
            $table->foreign('delivery_address_id')->references('id')->on('addresses');


            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unsignedBigInteger('aurora_id')->nullable()->unique();

            $table->index([DB::raw('name(64)')]);
        });

        Schema::create('customer_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');


            $table->timestampTz('last_submitted_order_at')->nullable();
            $table->timestampTz('last_dispatched_delivery_at')->nullable();
            $table->timestampTz('last_invoiced_at')->nullable();


            $table->unsignedBigInteger('number_deliveries')->default(0);
            $table->unsignedBigInteger('number_deliveries_type_order')->default(0);
            $table->unsignedBigInteger('number_deliveries_type_replacement')->default(0);

            $table->unsignedBigInteger('number_invoices')->default(0);
            $table->unsignedBigInteger('number_invoices_type_invoice')->default(0);
            $table->unsignedBigInteger('number_invoices_type_refund')->default(0);




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
        Schema::dropIfExists('customer_stats');
        Schema::dropIfExists('customers');
    }
}
