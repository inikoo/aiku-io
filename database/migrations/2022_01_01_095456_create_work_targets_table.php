<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 01 Jan 2022 23:18:58 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['work-day', 'extra-time', 'vacation', 'workplace-closed', 'festivity', 'rest-day'])->index();
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->jsonb('breaks');
            $table->string('checksum')->index()->nullable();
            $table->unsignedSmallInteger('gross_length')->comment('minutes including breaks')->default(0);
            $table->unsignedSmallInteger('length')->comment('minutes')->default(0);
            $table->timestampsTz();
            $table->unique(['type', 'checksum']);
        });


        Schema::create('work_targets', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('employee_id')->index();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedBigInteger('work_schedule_id')->index()->nullable();
            $table->foreign('work_schedule_id')->references('id')->on('work_schedules');

            $table->json('data')->nullable();
            $table->timestampsTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->index();
            $table->unique(['employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_schedules');
        Schema::dropIfExists('work_targets');
    }
}
