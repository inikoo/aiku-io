<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 01 Jan 2022 23:18:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_trackings', function (Blueprint $table) {
            $table->id();
            $table->morphs('time_trackable');
            $table->unsignedBigInteger('workplace_id')->index();
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->dateTimeTz('starts_at');
            $table->dateTimeTz('ends_at');
            $table->text('notes');

            $table->unsignedBigInteger('aurora_start_id')->nullable()->unique();
            $table->unsignedBigInteger('aurora_end_id')->nullable()->unique();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_records');
    }
}
