<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 12 Sep 2021 23:46:13 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;


class HumanResourcesController extends Controller
{

    protected string $module;
    protected array $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'human_resources.index',
                'name'    => __('Employees'),
                'current' => false
            ],
        ];



        $this->module = 'human_resources';
    }

    public function index(): Response
    {
        return Inertia::render(
            'HumanResources/Index',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('Human resources'),
                    'breadcrumbs' => data_set($this->breadcrumbs, "index.current", true),

                ],



            ]
        );

    }
}
