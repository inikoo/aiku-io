<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('trade_units', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->nullable()->index();
                $table->string('code')->index();
                $table->string('name', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('barcode')->index()->nullable();
                $table->float('weight')->nullable();
                $table->jsonb('dimensions')->nullable();

                $table->string('type')->default('piece')->index()->nullable()->comment('unit type');
                $table->unsignedBigInteger('image_id')->nullable();
                $table->foreign('image_id')->references('id')->on('images');
                $table->jsonb('data');
                $table->timestampsTz();
                $table->softDeletesTz();
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
        Schema::dropIfExists('trade_units');
    }
}
