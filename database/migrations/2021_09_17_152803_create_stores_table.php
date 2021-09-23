<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Sep 2021 23:38:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('state')->index();

            $table->string('slug')->index();
            $table->string('code');
            $table->string('name');

            $table->json('data');
            $table->timestampsTz();

            $table->string('url')->nullable();
            $table->string('currency');
            $table->string('locale');

            $table->softDeletesTz();
            $table->unsignedMediumInteger('legacy_id')->nullable();
            $table->unsignedSmallInteger('tenant_id');
            $table->unique(
                [
                    'legacy_id',
                    'tenant_id'
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
        Schema::dropIfExists('stores');
    }
}
