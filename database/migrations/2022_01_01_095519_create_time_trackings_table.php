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
            $table->enum('type',['clocking-machine','manual','self-manual','system'])->index();
            $table->nullableMorphs('subject');
            $table->unsignedBigInteger('time_tracking_id')->index()->nullable();
            $table->unsignedBigInteger('workplace_id')->nullable()->index();
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->dateTimeTz('clocked_at');
            $table->text('notes')->nullable();
            $table->timestampsTz();
            $table->nullableMorphs('created_by');
            $table->softDeletes();
            $table->nullableMorphs('deleted_by');
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('time_trackings', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['creating','open','closed','error'])->default('creating')->index();
            $table->morphs('subject');// Employee|Guest
            $table->unsignedBigInteger('workplace_id')->nullable()->index();
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->dateTimeTz('starts_at')->nullable();
            $table->dateTimeTz('ends_at')->nullable();

            $table->unsignedBigInteger('start_clocking_id')->nullable()->index();
            $table->foreign('start_clocking_id')->references('id')->on('clockings');
            $table->unsignedBigInteger('end_clocking_id')->nullable()->index();
            $table->foreign('end_clocking_id')->references('id')->on('clockings');
            $table->timestampsTz();
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
