<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 16 Aug 2021 06:32:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordTenantsTable extends Migration
{
    public function up()
    {



        Schema::create('countries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',2)->unique()->index();
            $table->string('code_iso3',3)->nullable()->index();
            $table->unsignedSmallInteger('code_iso_numeric')->nullable()->index();
            $table->unsignedInteger('geoname_id')->nullable()->index();

            $table->string('phone_code')->nullable();
            $table->string('currency_code')->nullable();

            $table->string('name');
            $table->string('continent');
            $table->string('capital');
            $table->string('timezone')->comment('Timezone in capital');

            $table->jsonb('data');
            $table->timestampsTz();
        });


        Schema::create('ip_geolocations', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->string('continent_code')->nullable()->index();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('geolocation_label')->nullable();
            $table->unsignedBigInteger('geoname_id')->nullable()->index();
            $table->jsonb('data')->nullable();
            $table->enum('status',['InProcess','OK','Error'])->default('InProcess')->index();
            $table->timestampsTz();

        });
        Schema::create('user_agents', function (Blueprint $table) {
            $table->id();
            $table->string('checksum',32)->index()->unique();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->text('software')->nullable();
            $table->string('os_code')->nullable();
            $table->string('device_type')->nullable()->index();

            $table->enum('status',['InProcess','OK','Error'])->default('InProcess');
            $table->jsonb('data')->nullable();
            $table->timestampsTz();
        });

        Schema::create('business_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->foreignId('business_type_id')->constrained();
            $table->string('country',2)->nullable();
            $table->jsonb('data');
            $table->timestampsTz();
        });
    }
    public function down()
    {
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('business_types');
        Schema::dropIfExists('user_agents');

        Schema::dropIfExists('ip_geolocations');
        Schema::dropIfExists('countries');
    }
}
