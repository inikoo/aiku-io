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
        Schema::create('patient_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('identity_document_type');
            $table->string('identity_document_number');
            $table->string('email');
            $table->string('phone');


            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('gender');


            $table->jsonb('data');
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
        Schema::dropIfExists('patient_contacts');
        Schema::dropIfExists('patients');

    }
}
