<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 14 Jan 2022 14:40:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */


use App\Http\Controllers\Web\WebController;

Route::get('/{website}', [WebController::class, 'show'])->name('index');
