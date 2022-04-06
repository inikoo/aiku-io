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
        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedMediumInteger('department_id')->nullable();
                $table->foreign('department_id')->references('id')->on('departments');
                $table->unsignedMediumInteger('family_id')->nullable();
                $table->foreign('family_id')->references('id')->on('families');
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
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
