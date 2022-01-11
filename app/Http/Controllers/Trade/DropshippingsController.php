<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 04:57:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;


class DropshippingsController extends Controller
{

    protected string $module;
    protected array $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = [
            'index' => [
                'route'   => 'dropshippings.index',
                'name'    => __('Dropshippings'),
                'current' => false
            ],
        ];



        $this->module = 'dropshippings';
    }

    public function index(): Response
    {
        return Inertia::render(
            'Dropshippings/Index',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('Dropshippings'),
                    'breadcrumbs' => data_set($this->breadcrumbs, "index.current", true),

                ],



            ]
        );

    }
}
