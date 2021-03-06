<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 04 Dec 2021 21:56:25 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('shippers', function (Blueprint $table) {
                $table->smallIncrements('id');
                $table->unsignedSmallInteger('api_shipper_id')->nullable()->index();


                $table->boolean('status')->default('true')->index();
                $table->string('code')->index();
                $table->string('name')->index();
                $table->string('contact_name', 256)->nullable();
                $table->string('company_name', 256)->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('website', 256)->nullable();

                $table->string('tracking_url')->nullable();

                $table->jsonb('data');
                $table->timestampsTz();
                $table->softDeletesTz();
                $table->unsignedBigInteger('aurora_id')->nullable()->index();
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
        Schema::dropIfExists('shippers');
    }
}
