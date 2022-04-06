<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 18 Nov 2021 16:19:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingSchemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('shipping_schemas', function (Blueprint $table) {
                $table->mediumIncrements('id');


                $table->unsignedMediumInteger('shop_id')->nullable()->index();
                if (app('currentTenant')->appType->code == 'ecommerce') {
                    $table->foreign('shop_id')->references('id')->on('shops');
                }

                $table->boolean('status')->default(true)->index();
                $table->string('slug')->index();

                $table->enum('type', ['current', 'in-reserve', 'deal'])->index();

                $table->string('name')->index();


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
        Schema::dropIfExists('shipping_schemas');
    }
}
