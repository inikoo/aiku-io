<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 27 Aug 2021 23:48:48 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuxTables extends Migration
{
    public function up()
    {
        //https://github.com/commerceguys/addressing
        /*
            Country code (The two-letter country code)
            Administrative area
            Locality (City)
            Dependent Locality
            Postal code
            Sorting code
            Address line 1
            Address line 2
            Organization
            Given name (First name)
            Additional name (Middle name / Patronymic)
            Family name (Last name)

         */

        Schema::create(
            'addresses',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('immutable')->default(false)->index();
                $table->string('address_line_1',255)->nullable();
                $table->string('address_line_2',255)->nullable();
                $table->string('sorting_code')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('locality')->nullable();
                $table->string('dependant_locality')->nullable();
                $table->string('administrative_area')->nullable();
                $table->string('country_code', 2)->nullable()->index();
                $table->string('checksum')->index()->nullable();
                $table->foreignId('owner_id')->nullable()->index();
                $table->string('owner_type')->nullable()->index();
                $table->string('owner_scope')->nullable();
                $table->unsignedSmallInteger('country_id')->nullable()->index();
                //$table->foreign('country_id')->references('id')->on('aiku.countries');
                $table->index(['checksum', 'owner_id', 'owner_type']);

                $table->timestampsTz();
            }
        );

        Schema::create('addressables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->index();
            $table->foreignId('addressable_id')->index();
            $table->string('addressable_type')->index();
            $table->string('scope')->nullable()->index();
            $table->string('status_info')->nullable();
            $table->timestampsTz();
        });



        Schema::create('dates', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->date('date')->unique();
            $table->string('holiday');
            $table->boolean('working');
            $table->json('data');
            $table->timestampsTz();
        });

        Schema::create('audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('event');
            $table->morphs('auditable');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1023)->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'user_type']);
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communal_image_id')->nullable();

            $table->string('imageable_type')->nullable()->index();
            $table->unsignedBigInteger('imageable_id')->nullable()->index();
            $table->text('caption')->nullable();

            $table->string('scope',16)->index();
            $table->smallInteger('rank')->default(0);
            $table->string('filename',255);
            $table->boolean('public')->default(false);

            $table->jsonb('compression')->nullable();


            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
            $table->index(['imageable_id', 'imageable_type', 'scope']);
            $table->unique(['communal_image_id', 'imageable_id', 'imageable_type', 'scope']);

        });




        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('common_attachment_id')->index();
         //   $table->foreign('common_attachment_id')->references('id')->on('common_attachments');

            $table->string('attachmentable_type', 64)->nullable()->index();
            $table->unsignedBigInteger('attachmentable_id')->nullable()->index();

            $table->string('scope', 64)->index();
            $table->string('filename')->index();
            $table->text('caption')->nullable();

            $table->boolean('public')->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable();

            $table->index(['attachmentable_id', 'attachmentable_type', 'scope'], 'attachments_idx1');
            $table->unique(['common_attachment_id', 'attachmentable_id', 'attachmentable_type', 'scope'], 'attachments_idx2');

        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('container');
            $table->unsignedMediumInteger('container_id');

            $table->string('type');
            $table->string('code')->index();
            $table->string('name');
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->nestedSet();
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedBigInteger('aurora_id')->nullable()->unique();
            $table->index(['container', 'container_id']);
        });


        Schema::create('categoriables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->index()->constrained();
            $table->morphs('categoriable');
            $table->timestampsTz();
            $table->unique(['category_id', 'categoriable_type', 'categoriable_id'], 'categoriables_idx');
        });




        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->morphs('contactable');

            $table->string('name',256)->nullable();
            $table->string('company',256)->nullable();

            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Make', 'Female', 'Other'])->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website',256)->nullable();
            $table->string('qq')->nullable();
            $table->unsignedBigInteger('address_id')->nullable()->index();
            $table->foreign('address_id')->references('id')->on('addresses');

            $table->string('identity_document_type')->nullable();
            $table->string('identity_document_number')->nullable();
            $table->string('tax_number')->nullable()->index();
            $table->enum('tax_number_status', ['valid', 'invalid', 'na', 'unknown'])->nullable()->default('na');

            $table->jsonb('data')->nullable();
            $table->timestampsTz();
        });
    }


    public function down()
    {
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('categoriables');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('images');
        Schema::dropIfExists('audits');
        Schema::dropIfExists('dates');
        Schema::dropIfExists('addressables');
        Schema::dropIfExists('addresses');
    }
}
