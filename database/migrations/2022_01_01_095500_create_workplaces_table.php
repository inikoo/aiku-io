<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 02 Jan 2022 15:27:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workplaces', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['hq','satellite','branch','home','workplace'])->index();
            $table->string('slug');
            $table->string('name');
            $table->unsignedSmallInteger('timezone_id');
            $table->unsignedBigInteger('address_id')->nullable()->index();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->morphs('owner');

            $table->timestamps();
            $table->softDeletesTz();
        });

        Schema::create('workplace_users', function (Blueprint $table) {
            $table->unsignedBigInteger('workplace_id')->index();
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->morphs('workplace_user');
            $table->timestamps();
            $table->unique(['workplace_id','workplace_user_type','workplace_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workplace-ables');
        Schema::dropIfExists('workplaces');
    }
}
