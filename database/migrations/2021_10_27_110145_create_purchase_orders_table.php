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

            $purchaseOrderStates = ['in-process', 'submitted',  'confirmed', 'dispatched', 'delivered','cancelled'];
            $table->enum('state',$purchaseOrderStates)->index()->default('in-process');

            $table->boolean('has_backorder')->default(false);
            $table->unsignedSmallInteger('number_deliveries')->default(0);
            $table->enum('backorder_state',
                         ['na',  'confirmed', 'dispatched']
            )->index()->default('na');
            $table->enum('frontorder_state',
                         ['na',  'dispatched', 'delivered']
            )->index()->default('na');
            $table->json('data')->nullable();
            $table->date('date')->index();
            $table->timestampsTz();
            $table->dateTimeTz('submitted_at')->nullable();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('purchase_order_id')->nullable()->index();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->timestamps();
        });

        Schema::create('procurement_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('number')->index()->nullable();
            $table->morphs('vendor');

            //enum('InProcess','Consolidated','Dispatched','Received','Checked','ReadyToPlace','Placed','Costing','Cancelled','InvoiceChecked')
            $table->enum('state',
                         [
                             'in-process',
                             'consolidated',
                             'dispatched',
                             'received',
                             'checked',
                             'ready-to-place',
                             'placed',
                             'costing',
                             'costing-done',
                             'cancelled',
                         ]
            )->index();

            $table->json('data')->nullable();


            $table->timestampsTz();
            $table->date('date')->index();
            $table->dateTimeTz('dispatched_at')->index()->nullable();
            $table->dateTimeTz('received_at')->index()->nullable();
            $table->dateTimeTz('placed_at')->index()->nullable();
            $table->dateTimeTz('cancelled_at')->index()->nullable();

            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('procurement_delivery_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('procurement_delivery_id')->nullable()->index();
            $table->foreign('procurement_delivery_id')->references('id')->on('procurement_deliveries');
            $table->timestamps();
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
        Schema::dropIfExists('purchase_orders');
    }
}
