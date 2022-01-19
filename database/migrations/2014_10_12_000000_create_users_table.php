<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();
            $table->string('password');
            $table->morphs('userable');
            $table->string('name');
            $table->boolean('status')->default(true)->index();
            $table->unsignedSmallInteger('language_id');
            //$table->foreign('language_id')->references('id')->on('aiku.languages');
            $table->unsignedSmallInteger('timezone_id');
            //$table->foreign('timezone_id')->references('id')->on('aiku.timezones');

            $table->jsonb('data');
            $table->jsonb('settings');
            $table->jsonb('errors');
            $table->timestampsTz();
            $table->softDeletesTz();



            $table->unsignedBigInteger('aurora_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
