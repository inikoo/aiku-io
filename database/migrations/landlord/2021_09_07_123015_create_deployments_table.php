<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 07 Sep 2021 20:34:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_admins', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->string('email')->unique();

            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('deployments', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('version');
            $table->string('hash');
            $table->string('state')->default('deploying');
            $table->json('data');
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
        Schema::dropIfExists('deployments');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('account_admins');

    }
}
