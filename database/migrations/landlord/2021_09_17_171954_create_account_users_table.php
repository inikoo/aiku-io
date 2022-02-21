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
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->timestampsTz();
        });

        Schema::create('tenant_users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->string('username')->unique();
            $table->string('password');
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_users');
        Schema::dropIfExists('admin_users');
    }
}
