<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 21 Aug 2021 03:51:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render(
        'Dashboard',
        [
            'title'       => __('Dashboard'),
            'breadcrumbs' => []
        ]
    );
})->name('index');
