<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 18 Sep 2021 01:48:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('admin_users', function (Blueprint $table) {

            $table->smallIncrements('id');
            $table->unsignedSmallInteger('account_admin_id');
            $table->foreign('account_admin_id')->references('id')->on('account_admins');

            $table->string('username')->unique();
            $table->string('password');


            $table->boolean('status')->default(true)->index();
            $table->unsignedSmallInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedSmallInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('timezones');

            $table->jsonb('data');
            $table->jsonb('settings');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->timestampsTz();
            $table->softDeletesTz();

        });

        Schema::create('users', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->string('middleware_group');
            $table->boolean('admin')->default(false)->index();


            $table->string('jar_username')->unique()->nullable();
            $table->string('username')->nullable();
            $table->string('password');

            $table->morphs('userable');
            $table->string('name')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->unsignedSmallInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedSmallInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('timezones');

            $table->jsonb('data');
            $table->jsonb('settings');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['tenant_id','username']);
            $table->unsignedBigInteger('aurora_id')->nullable()->index();


        });

        Schema::create('user_stats', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->foreign('id')->references('id')->on('users');
            $table->dateTimeTz('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->unsignedInteger('login_count')->default(0);
            $table->dateTimeTz('last_fail_login_at')->nullable();
            $table->string('last_fail_login_ip', 45)->nullable();
            $table->unsignedInteger('fail_login_count')->default(0);

            $table->timestampsTz();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });



    }


    public function down()
    {
        Schema::dropIfExists('user_website');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('user_stats');
        Schema::dropIfExists('users');
        Schema::dropIfExists('admin_users');
    }
}
