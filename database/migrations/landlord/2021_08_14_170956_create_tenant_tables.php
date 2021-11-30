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

class CreateTenantTables extends Migration
{
    public function up()
    {
        Schema::create('aiku', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('version')->nullable();
            $table->dateTimeTz('deployed_at')->nullable();
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->timestampsTz();

        });

        Schema::create('countries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code', 2)->unique()->index();
            $table->string('iso3', 3)->nullable()->index();
            $table->string('phone_code')->nullable();
            $table->string('name');
            $table->string('continent')->nullable();
            $table->string('capital')->nullable();
            $table->unsignedSmallInteger('timezone_id')->nullable()->comment('Timezone in capital')->index();
            $table->unsignedSmallInteger('currency_id')->nullable()->index();
            $table->string('type')->nullable()->index()->default('independent');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();

        });


        Schema::create('timezones', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique()->index();
            $table->bigInteger('offset')->nullable()->comment('in hours');
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('location');
            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code')->unique()->index();
            $table->string('name')->nullable()->index();
            $table->string('original_name')->nullable();
            $table->string('status')->nullable()->index();
            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code')->unique()->index();
            $table->string('name')->index();
            $table->string('symbol');

            $table->smallInteger('fraction_digits');
            $table->string('status')->nullable()->index();
            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('country_language', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('country_id');
            $table->unsignedSmallInteger('language_id');
            $table->unsignedSmallInteger('priority')->default(1)->index();
            $table->string('status')->nullable()->index();
            $table->timestampsTz();
            $table->unique(['country_id', 'language_id']);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        Schema::create('country_timezone', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('country_id');
            $table->unsignedSmallInteger('timezone_id');
            $table->unsignedSmallInteger('priority')->default(1)->index();
            $table->string('type')->nullable()->index();
            $table->timestampsTz();
            $table->unique(['country_id', 'timezone_id']);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('timezone_id')->references('id')->on('timezones')->onDelete('cascade');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });

        Schema::create('ip_geolocations', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->string('continent_code')->nullable()->index();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('geolocation_label')->nullable();
            $table->unsignedBigInteger('geoname_id')->nullable()->index();
            $table->jsonb('data')->nullable();
            $table->enum('status', ['InProcess', 'OK', 'Error'])->default('InProcess')->index();
            $table->timestampsTz();
        });
        Schema::create('user_agents', function (Blueprint $table) {
            $table->id();
            $table->string('checksum', 32)->index()->unique();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->text('software')->nullable();
            $table->string('os_code')->nullable();
            $table->string('device_type')->nullable()->index();

            $table->enum('status', ['InProcess', 'OK', 'Error'])->default('InProcess');
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
            $table->string('nickname')->unique();
            $table->string('name')->comment('E.g. company name');
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->string('email')->unique();

            $table->foreignId('business_type_id')->constrained();
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->unsignedSmallInteger('currency_id')->nullable();
            $table->unsignedSmallInteger('language_id')->nullable();
            $table->unsignedSmallInteger('timezone_id')->nullable();
            $table->jsonb('data');
            $table->timestampsTz();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('timezone_id')->references('id')->on('timezones');

        });




    }

    public function down()
    {


        Schema::dropIfExists('tenants');
        Schema::dropIfExists('business_types');
        Schema::dropIfExists('user_agents');
        Schema::dropIfExists('ip_geolocations');
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('timezone_id');
            $table->dropColumn('currency_id');

        });
        Schema::dropIfExists('country_timezone');
        Schema::dropIfExists('country_language');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('timezones');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('aiku');

    }
}
