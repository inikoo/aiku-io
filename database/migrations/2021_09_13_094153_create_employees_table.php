<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Sep 2021 17:42:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_positions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->json('data')->nullable();
            $table->timestampsTz();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->index();
            $table->string('worker_number')->nullable();
            $table->string('job_title')->nullable();

            $table->enum('type', ['employee', 'volunteer', 'temporal-worker', 'work-experience'])->default('employee');
            $table->enum('state', ['hired', 'working', 'left'])->default('working');
            $table->date('employment_start_at')->nullable();
            $table->date('employment_end_at')->nullable();
            $table->string('emergency_contact', 1024)->nullable();
            $table->jsonb('salary')->nullable();
            $table->jsonb('working_hours')->nullable();

            $table->jsonb('data');
            $table->jsonb('errors');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('job_positions');
    }
}
