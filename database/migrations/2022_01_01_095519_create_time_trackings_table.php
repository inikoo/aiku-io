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
        Schema::create('clockings', function (Blueprint $table) {
            $table->id();
            $table->enum('source',['clocking-machine','manual','self-manual','system'])->index();

            $table->nullableMorphs('creator');
            $table->nullableMorphs('clockable');
            $table->unsignedBigInteger('time_tracking_id')->index()->nullable();
            $table->dateTimeTz('clocked_at');
            $table->timestampsTz();
            $table->softDeletes();
            $table->nullableMorphs('deleter');
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('time_trackings', function (Blueprint $table) {
            $table->id();
            $table->morphs('time_trackable');
            $table->unsignedBigInteger('workplace_id')->index();
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->dateTimeTz('starts_at');
            $table->dateTimeTz('ends_at');

            $table->unsignedBigInteger('start_clocking_id')->index();
            $table->foreign('start_clocking_id')->references('id')->on('clockings');
            $table->unsignedBigInteger('end_clocking_id')->index();
            $table->foreign('end_clocking_id')->references('id')->on('clockings');
            $table->text('notes');
        });

        Schema::table('clockings', function (Blueprint $table) {
            // $table->integer('holding_id')->unsigned()->index()->nullable();

            $table->foreign('time_tracking_id')->references('id')->on('time_trackings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('drop table if exists time_trackings cascade');
        DB::statement('drop table if exists clockings cascade');

        //Schema::dropIfExists('timesheet_records');
        //Schema::dropIfExists('clockings');
    }
}
