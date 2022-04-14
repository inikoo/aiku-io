<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 13 Apr 2022 18:03:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Marketing;


use App\Actions\Marketing\Department\IndexDepartment;
use App\Http\Controllers\Controller;
use App\Models\Marketing\Shop;
use Inertia\Response;


class DepartmentController extends Controller
{


    public function index(Shop $shop): Response
    {
        return IndexDepartment::make()->asInertia($shop);
    }

    public function show(Shop $shop): Response
    {
        return ShowDepartment::make()->asInertia($shop);
    }


}
