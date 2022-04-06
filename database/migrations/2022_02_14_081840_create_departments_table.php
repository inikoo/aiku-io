<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 14 Feb 2022 16:31:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

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
            Schema::create('departments', function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->string('slug')->nullable()->index();

                $table->unsignedMediumInteger('shop_id')->nullable();
                $table->foreign('shop_id')->references('id')->on('shops');
                $table->enum('state', ['creating', 'active', 'suspended', 'discontinuing', 'discontinued'])->nullable()->index();
                $table->string('code')->index();
                $table->string('name', 255)->nullable();
                $table->text('description')->nullable();


                $table->timestampstz();
                $table->softDeletesTz();
                $table->unsignedBigInteger('aurora_id')->nullable()->unique();
            });

            Schema::create('department_stats', function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedSmallInteger('department_id')->index();
                $table->foreign('department_id')->references('id')->on('departments');


                $table->unsignedBigInteger('number_families')->default(0);
                $familyStates = ['creating', 'active', 'suspended', 'discontinuing', 'discontinued'];
                foreach ($familyStates as $familyState) {
                    $table->unsignedBigInteger('number_families_state_'.str_replace('-', '_', $familyState))->default(0);
                }

                $table->unsignedBigInteger('number_products')->default(0);
                $productStates = ['creating', 'active', 'suspended', 'discontinuing', 'discontinued'];
                foreach ($productStates as $productState) {
                    $table->unsignedBigInteger('number_products_state_'.str_replace('-', '_', $productState))->default(0);
                }

                $table->timestampsTz();
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
        Schema::dropIfExists('department_stats');
        Schema::dropIfExists('departments');
    }
};
