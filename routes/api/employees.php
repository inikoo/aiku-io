<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 22 Dec 2021 22:39:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Illuminate\Support\Facades\Route;
use App\Actions\Helpers\AttachmentModel\DownloadEmployeeAttachment;
use App\Actions\Helpers\AttachmentModel\ShowEmployeeAttachment;
use App\Actions\Helpers\AttachmentModel\ShowEmployeeAttachments;
use App\Actions\HumanResources\Employee\ShowEmployee;
use App\Actions\HumanResources\Employee\ShowEmployees;
use App\Actions\HumanResources\Employee\StoreEmployee;
use App\Actions\HumanResources\Employee\UpdateEmployee;


Route::get('', ShowEmployees::class)->name('index');
Route::post('', StoreEmployee::class)->name('store');
Route::get('{employee}',ShowEmployee::class)->name('employee.show');
Route::patch('{employee}',UpdateEmployee::class)->name('employee.update');
Route::get('{employee}/attachments',ShowEmployeeAttachments::class)->name('attachments.index');
Route::get('{employee}/attachments/{attachmentModel}',ShowEmployeeAttachment::class)->name('attachment.show');
Route::get('{employee}/attachments/{attachmentModel}/download',DownloadEmployeeAttachment::class)->name('attachment.downland');


