<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 22 Dec 2021 22:39:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\HumanResources\Employee\ShowEmployees;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;
use App\Actions\Media\Attachment\Employee\DestroyEmployeeAttachment;
use App\Actions\Media\Attachment\Employee\DownloadEmployeeAttachment;
use App\Actions\Media\Attachment\Employee\ShowEmployeeAttachment;
use App\Actions\Media\Attachment\Employee\ShowEmployeeAttachments;
use App\Actions\Media\Attachment\Employee\StoreEmployeeAttachment;
use App\Actions\Media\Attachment\Employee\UpdateEmployeeAttachment;
use App\Actions\Media\Image\Employee\DestroyEmployeeImage;
use App\Actions\Media\Image\Employee\DisplayEmployeeImage;
use App\Actions\Media\Image\Employee\ShowEmployeeImage;
use App\Actions\Media\Image\Employee\ShowEmployeeImages;
use App\Actions\Media\Image\Employee\StoreEmployeeImage;
use App\Actions\Media\Image\Employee\UpdateEmployeeImage;
use Illuminate\Support\Facades\Route;


Route::get('', ShowEmployees::class)->name('index');
Route::post('', StoreEmployee::class)->name('store');
Route::get('{employee}',ShowEmployee::class)->name('show');
Route::patch('{employee}',UpdateEmployee::class)->name('update');

Route::get('{employee}/attachments',ShowEmployeeAttachments::class)->name('attachments.index');
Route::post('{employee}/attachments',StoreEmployeeAttachment::class)->name('attachment.store');
Route::get('{employee}/attachments/{attachment}',ShowEmployeeAttachment::class)->name('attachment.show');
Route::get('{employee}/attachments/{attachment}/download',DownloadEmployeeAttachment::class)->name('attachment.downland');
Route::patch('{employee}/attachments/{attachment}',UpdateEmployeeAttachment::class)->name('attachment.update');
Route::delete('{employee}/attachments/{attachment}',DestroyEmployeeAttachment::class)->name('attachment.destroy');

Route::get('{employee}/images',ShowEmployeeImages::class)->name('images.index');
Route::post('{employee}/images',StoreEmployeeImage::class)->name('attachment.store');
Route::get('{employee}/images/{image}', ShowEmployeeImage::class)->name('image.show');
Route::get('{employee}/images/{image}/display',DisplayEmployeeImage::class)->name('image.display');
Route::patch('{employee}/images/{image}',UpdateEmployeeImage::class)->name('image.update');
Route::delete('{employee}/images/{image}',DestroyEmployeeImage::class)->name('image.destroy');
