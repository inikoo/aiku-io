<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 10 Dec 2021 21:57:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeliveryNoteItemPicking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'delivery_note_item_picking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_note_item_id')->constrained();
            $table->foreignId('picking_id')->constrained();
            $table->timestampsTz();
            $table->unique(
                [
                    'delivery_note_item_id',
                    'picking_id'
                ]
            );
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_note_item_picking');

    }
}
