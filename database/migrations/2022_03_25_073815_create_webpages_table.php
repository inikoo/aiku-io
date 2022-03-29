<?php

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
        Schema::create('webpages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->index();
            $table->string('name');

            $webpageTypes = [
                'home',
                'info'
            ];

            $table->enum('type', $webpageTypes)->nullable();
            $table->unsignedSmallInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->enum('state', ['in-process', 'launched', 'archived'])->default('in-process')->index();
            $table->enum('status', ['in-process', 'online', 'maintenance', 'offline', 'archived'])->default('in-process')->index();
            $table->boolean('locked')->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('webpage_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('webpage_id')->index();
            $table->foreign('webpage_id')->references('id')->on('webpages');
            $table->unsignedBigInteger('number_layouts')->default(0);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestampsTz();
        });

        Schema::create('webpage_layouts', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true)->index();
            $table->boolean('logged_in')->nullable();
            $table->boolean('customer_id')->nullable();

            $table->unsignedSmallInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->unsignedSmallInteger('webpage_id')->index();
            $table->foreign('webpage_id')->references('id')->on('webpages');

            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('webpage_layout_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('webpage_layout_id')->index();
            $table->foreign('webpage_layout_id')->references('id')->on('webpage_layouts');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestampsTz();
        });

        Schema::table('website_layouts', function (Blueprint $table) {
            $table->foreign('home_webpage_id')->references('id')->on('webpages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('website_layouts', function (Blueprint $table) {
            $table->dropForeign('home_webpage_id');
        });
        Schema::dropIfExists('webpage_layouts');
        Schema::dropIfExists('webpages');
    }
};
