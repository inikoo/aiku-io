<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 16 Aug 2021 06:32:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordTenantsTable extends Migration
{
    public function up()
    {

        Schema::create('business_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->jsonb('data');
            $table->timestampsTz();
        });

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->foreignId('business_type_id')->constrained();
            $table->jsonb('data');
            $table->timestampsTz();
        });
    }
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
