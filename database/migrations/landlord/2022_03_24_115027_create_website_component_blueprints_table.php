<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 25 Mar 2022 00:06:52 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

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
        Schema::create('website_component_blueprints', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->enum('source', ['aiku', 'tenant'])->default('aiku');
            $table->enum('type', ['footer', 'header', 'block']);
            $table->string('template');
            $table->string('name');
            $table->jsonb('sample_arguments');
            $table->jsonb('settings');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['type', 'template','name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_component_blueprints');
    }
};
