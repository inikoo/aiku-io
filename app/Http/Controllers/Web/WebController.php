<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Jan 2022 03:41:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Web;


use App\Actions\Web\Website\ShowWebsite;
use App\Actions\Web\Website\WebsiteIndex;
use App\Http\Controllers\Controller;
use App\Models\Web\Website;
use Inertia\Response;


class WebController extends Controller
{


    public function index(): Response
    {
        return WebsiteIndex::make()->asInertia();
    }

    public function show(Website $website): Response
    {
        return ShowWebsite::make()->asInertia($website);
    }


}
