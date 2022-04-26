<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('currentTenant')->appType->code == 'staffing') {

            Schema::create('recruiters', function (Blueprint $table) {
                $table->id();
                $table->nullableMorphs('recruiterable');
                $table->string('nickname')->index();
                $table->string('name', 256)->nullable()->index();;
                $table->timestampsTz();
                $table->softDeletesTz();
            });

            Schema::create('applicants', function (Blueprint $table) {
                $table->id();

                $table->foreignId('recruiter_id')->nullable()->constrained();


                $table->string('name', 256)->nullable()->index();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('identity_document_type')->nullable();
                $table->string('identity_document_number')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->enum('gender', ['Make', 'Female', 'Other'])->nullable();

                $table->enum('type', ['employee', 'volunteer', 'temporal-worker', 'work-experience'])->default('employee');
                $table->enum('state', ['hired', 'working', 'left'])->default('working');
                $table->date('employment_start_at')->nullable();
                $table->date('employment_end_at')->nullable();
                $table->string('emergency_contact', 1024)->nullable();

                $table->jsonb('data');
                $table->jsonb('errors');
                $table->timestampsTz();
                $table->softDeletesTz();
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
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('recruiters');
    }
};
