<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 08 Feb 2022 21:04:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_stats', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('scope');

            $periods           = ['all', '1y', '1q', '1m', ',1w', 'ytd', 'qtd', 'mtd', 'wtd', 'lm', 'lw', 'yda', 'tdy'];
            $periods_last_year = ['1y', '1q', '1m', ',1w', 'ytd', 'qtd', 'mtd', 'wtd', 'lm', 'lw', 'yda', 'tdy'];
            $previous_years    = ['py1', 'py2', 'py3', 'py4', 'py5'];
            $previous_quarters = ['pq1', 'pq2', 'pq3', 'pq4', 'pq5'];

            foreach ($periods as $col) {
                $table->decimal($col)->default(0);
            }
            foreach ($periods_last_year as $col) {
                $table->decimal($col.'_ly')->default(0);
            }
            foreach ($previous_years as $col) {
                $table->decimal($col)->default(0);
            }
            foreach ($previous_quarters as $col) {
                $table->decimal($col)->default(0);
            }

            $table->unique(['model_id','model_type','scope']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_stats');
    }
}
