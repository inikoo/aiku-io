<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 28 Dec 2021 00:10:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunalImagesTable extends Migration
{

    protected $connection = 'media';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('communal_images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->unsignedSmallInteger('relations')->default(0);
            $table->unsignedSmallInteger('tenants')->default(0);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['imageable_type', 'imageable_id']);
            $table->unsignedSmallInteger('deleted_relations')->default(0);

        });

        Schema::create('communal_image_tenant', function (Blueprint $table) {
            $table->unsignedBigInteger('communal_image_id');
            $table->foreign('communal_image_id')->references('id')->on('communal_images');
            $table->unsignedBigInteger('tenant_id')->index();
            $table->timestampsTz();
            $table->unique(['communal_image_id','tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('communal_image_tenant');
        Schema::dropIfExists('communal_images');


    }
}
