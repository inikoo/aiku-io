<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['dependant', 'adult'])->index();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('contact_patient', function (Blueprint $table) {
            $table->id();
            $table->string('relation');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->unique(['contact_id', 'patient_id']);
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
        Schema::dropIfExists('contact_patient');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('patients');
    }
}
