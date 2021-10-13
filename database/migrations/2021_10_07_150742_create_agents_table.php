<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 08 Oct 2021 17:52:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {

            $table->mediumIncrements('id');
            $table->string('code')->index();

            $table->morphs('owner');
            $table->string('name');




            $table->unsignedSmallInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('aiku.currencies');




            $table->jsonb('settings');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
