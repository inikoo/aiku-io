<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 23:02:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMediaTables extends Migration
{

    protected $connection = 'media';


    public function up()
    {
        Schema::create('raw_images', function (Blueprint $table) {
            $table->id();
            $table->string('checksum', 32)->index()->unique();
            $table->unsignedBigInteger('filesize')->index();
            $table->double('megapixels')->index();
            $table->string('mime');
            //$table->binary('image_data');
            $table->timestampsTz();
            $table->softDeletesTz();
        });



        DB::statement('ALTER TABLE raw_images ADD image_data  LONGBLOB');


        Schema::create('processed_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_image_id');
            $table->foreign('raw_image_id')->references('id')->on('raw_images');

            $table->string('checksum', 32)->index()->unique();

            $table->unsignedBigInteger('filesize')->index();
            $table->double('megapixels')->index();
            $table->string('mime');

            $table->jsonb('data');
            //$table->binary('image_data');

            $table->timestampsTz();
            $table->softDeletesTz();
        });
        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        DB::statement('ALTER TABLE processed_images ADD image_data  LONGBLOB');


        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('checksum')->unique()->index();
            $table->unsignedBigInteger('filesize')->index();
            $table->string('mime');
            //$table->binary('attachment_data')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
        });

        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        DB::statement('ALTER TABLE attachments ADD attachment_data  LONGBLOB');

    }


    public function down()
    {
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('processed_images');
        Schema::dropIfExists('raw_images');
    }
}
