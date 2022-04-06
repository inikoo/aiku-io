<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 06 Jan 2022 03:55:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivingNotesTable extends Migration
{
    /**
     * Run the migrations.
     * Wrapper around Procurement Deliveries , Fulfilment Asset deliveries and Returns
     *
     * @return void
     */
    public function up()
    {
        if (in_array(app('currentTenant')->appType->code, ['ecommerce', 'agent'])) {
            Schema::create('receiving_notes', function (Blueprint $table) {
                $table->id();

                $table->morphs('sender');
                $table->string('reference')->index();
                $table->enum('state',
                             [
                                 'in-process',
                                 'consolidated',
                                 'dispatched',
                                 'received',
                                 'checked',
                                 'ready-to-place',
                                 'placed',
                                 'costing',
                                 'costing-done',
                                 'cancelled',
                             ]
                )->index();

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
        Schema::dropIfExists('receiving_notes');
    }
}
