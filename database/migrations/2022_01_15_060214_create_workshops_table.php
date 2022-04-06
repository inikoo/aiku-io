<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 15 Jan 2022 14:34:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('currentTenant')->appType->code == 'ecommerce') {
            Schema::create('workshops', function (Blueprint $table) {
                $table->mediumIncrements('id');
                $table->string('code')->index();
                $table->morphs('owner');
                $table->string('name');
                $table->jsonb('settings');
                $table->jsonb('data');
                $table->timestampsTz();
                $table->softDeletesTz();
                $table->unsignedBigInteger('aurora_id');
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
        Schema::dropIfExists('workshops');
    }
}
