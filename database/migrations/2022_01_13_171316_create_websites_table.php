<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 01:28:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->enum('state',['in-process','active','maintenance','closed'])->index();
            $table->string('slug')->index();
            $table->string('code')->index();
            $table->string('url');
            $table->string('name');
            $table->jsonb('settings');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->timestampTz('launched_at')->nullable();
            $table->timestampTz('closed_at')->nullable();

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
        Schema::dropIfExists('websites');
    }
}