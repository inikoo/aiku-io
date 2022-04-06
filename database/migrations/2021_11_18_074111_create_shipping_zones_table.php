<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 18 Nov 2021 16:26:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('shipping_zones', function (Blueprint $table) {
                $table->mediumIncrements('id');

                $table->unsignedMediumInteger('shipping_schema_id')->nullable()->index();
                $table->foreign('shipping_schema_id')->references('id')->on('shipping_schemas');
                $table->boolean('status')->default(true)->index();
                $table->string('slug')->index();
                $table->string('code')->index();

                $table->string('name')->index();
                $table->smallInteger('rank')->default(0);
                $table->jsonb('settings');
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
        Schema::dropIfExists('shipping_zones');
    }
}
