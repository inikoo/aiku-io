<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->index()->nullable();
            $table->morphs('vendor');

            $table->enum('state',['in-process','submitted','no-received','confirmed','manufactured','qc-pass','inputted','dispatched','received','checked','placed','costing','invoice-checked','cancelled'])->index()->default('in-process');

            $table->json('data')->nullable();
            $table->date('date')->index();

            $table->timestampsTz();
            $table->dateTimeTz('submitted_at')->nullable();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
