<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 02:52:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('website_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('website_component_blueprint_id')->index();
            $table->enum('type', ['footer', 'header', 'block'])->index();
            $table->enum('status', ['published', 'archived', 'preview','library'])->index();
            $table->unsignedSmallInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->string('template');
            $table->string('name');
            $table->jsonb('arguments');
            $table->jsonb('settings');

            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('website_layouts', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');

            //$table->enum('state',['construction','launched','closed'])->default('in-process')->index();
            //$table->enum('status',['construction','live','maintenance','offline'])->default('in-process')->index();

            $table->boolean('status')->nullable();

            $table->unsignedBigInteger('home_webpage_id')->index()->nullable();

            $table->unsignedBigInteger('footer_preview_id')->index()->nullable();
            $table->foreign('footer_preview_id')->references('id')->on('website_components');

            $table->unsignedBigInteger('footer_published_id')->index()->nullable();
            $table->foreign('footer_published_id')->references('id')->on('website_components');

            $table->unsignedBigInteger('header_preview_id')->index()->nullable();
            $table->foreign('header_preview_id')->references('id')->on('website_components');

            $table->unsignedBigInteger('header_published_id')->index()->nullable();
            $table->foreign('header_published_id')->references('id')->on('website_components');

            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::table('websites', function (Blueprint $table) {
            $table->foreign('current_layout_id')->references('id')->on('website_layouts');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_layouts');
        Schema::dropIfExists('website_components');
    }
};
