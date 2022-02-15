<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->nullable()->index();

            $table->unsignedMediumInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->unsignedMediumInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');

            $table->string('code')->index();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();


            $table->timestampstz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('family_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('family_id')->index();
            $table->foreign('family_id')->references('id')->on('families');
            $table->unsignedBigInteger('number_products')->default(0);

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
        Schema::dropIfExists('family_stats');
        Schema::dropIfExists('families');
    }
};
