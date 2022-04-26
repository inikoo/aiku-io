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
            $table->string('department')->nullable();
            $table->string('team')->nullable();
            $table->json('roles');
            $table->json('data')->nullable();
            $table->timestampsTz();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nickname')->index();
            //these are no normal, hydra-table from contact
            $table->string('name', 256)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('identity_document_type')->nullable();
            $table->string('identity_document_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Make', 'Female', 'Other'])->nullable();
            //=====
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
            $table->jsonb('job_position_scopes');

            $table->jsonb('errors');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        Schema::create('employee_job_position', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_position_id')->index();
            $table->foreign('job_position_id')->references('id')->on('job_positions');
            $table->unsignedBigInteger('employee_id')->index();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->timestampsTz();
            $table->unique(['job_position_id', 'employee_id']);
        });

        if (app('currentTenant')->appType->code == 'staffing') {
            Schema::create('employee_staffing_stats', function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedMediumInteger('employee_id')->index();
                $table->foreign('employee_id')->references('id')->on('employees');


                $table->unsignedSmallInteger('number_applicants')->default(0);

                $table->timestamps();
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
        Schema::dropIfExists('employee_job_position');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('job_positions');
    }
}
