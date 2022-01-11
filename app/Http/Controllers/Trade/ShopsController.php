<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 03:50:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;


class ShopsController extends Controller
{

    protected string $module;
    protected array $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'shops.index',
                'name'    => __('Stores'),
                'current' => false
            ],
        ];



        $this->module = 'shops';
    }

    public function index(): Response
    {
        return Inertia::render(
            'Shops/Index',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('Stores'),
                    'breadcrumbs' => data_set($this->breadcrumbs, "index.current", true),

                ],



            ]
        );

    }
}
