<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 20 Feb 2022 06:29:05 Malaysia Time, Kuala Lumpur, Malaysia
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
        Schema::create('providers', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('tenant_id')->nullable()->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');

            $table->morphs('owner');

            $table->boolean('status')->default(true)->index();
            $table->string('code')->index();
            $table->string('name');
            $table->string('company_name',256)->nullable();
            $table->string('contact_name',256)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('address_id')->nullable()->index();

            $table->jsonb('location');
            $table->unsignedSmallInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->jsonb('settings');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
};
