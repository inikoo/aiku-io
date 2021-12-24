<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 22 Dec 2021 22:39:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */


use App\Actions\Helpers\Attachment\Employee\DownloadEmployeeAttachment;
use App\Actions\Helpers\Attachment\Employee\ShowEmployeeAttachment;
use App\Actions\Helpers\Attachment\Employee\ShowEmployeeAttachments;
use App\Actions\Helpers\Attachment\Employee\StoreEmployeeAttachment;
use App\Actions\Helpers\Attachment\Employee\UpdateEmployeeAttachment;
use Illuminate\Support\Facades\Route;
use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\HumanResources\Employee\ShowEmployees;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;


Route::get('', ShowEmployees::class)->name('index');
Route::post('', StoreEmployee::class)->name('store');
Route::get('{employee}',ShowEmployee::class)->name('show');
Route::patch('{employee}',UpdateEmployee::class)->name('update');

Route::get('{employee}/attachments',ShowEmployeeAttachments::class)->name('attachments.index');
Route::post('{employee}/attachments',StoreEmployeeAttachment::class)->name('attachment.store');

Route::get('{employee}/attachments/{attachment}',ShowEmployeeAttachment::class)->name('attachment.show');
Route::get('{employee}/attachments/{attachment}/download',DownloadEmployeeAttachment::class)->name('attachment.downland');

Route::patch('{employee}/attachments/{attachment}',UpdateEmployeeAttachment::class)->name('attachment.update');

