<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 30 Nov 2021 23:55:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjustsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjusts', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('shop_id')->nullable()->index();
            if (app('currentTenant')->appType->code == 'ecommerce') {
                $table->foreign('shop_id')->references('id')->on('shops');
            }
            $table->enum('type',['refund','credit','other'])->index();
            $table->string('slug')->index();
            $table->timestampsTz();
            $table->index(
                [
                    'shop_id',
                    'type'
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adjusts');
    }
}
