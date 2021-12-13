<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryNoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained();

            //$table->unsignedBigInteger('shop_id')->index();
            //$table->foreign('shop_id')->references('id')->on('shops');
            //$table->unsignedBigInteger('customer_id')->index();
            //$table->foreign('customer_id')->references('id')->on('customers');


            $table->unsignedBigInteger('delivery_note_id')->index();
            $table->foreign('delivery_note_id')->references('id')->on('delivery_notes');

            $table->foreignId('order_id')->nullable()->constrained();

            $table->foreignId('product_id')->nullable()->constrained();
            $table->decimal('quantity', 16, 3);
            $table->jsonb('data');

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unsignedBigInteger('aurora_otf_id')->nullable()->index();
            $table->unsignedBigInteger('aurora_itf_id')->nullable()->index();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_note_items');
    }
}
