<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 11 Jan 2022 03:50:31 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Models\Trade\Shop;
use Inertia\Inertia;
use Inertia\Response;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\QueryBuilder;


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
        $shops = QueryBuilder::for(Shop::class)->where('type','b2b')
            ->allowedSorts(['code', 'name'])
            ->paginate()
            ->withQueryString();


        return Inertia::render(
            'Shops/Shops',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => __('Stores'),
                    'breadcrumbs' => data_set($this->breadcrumbs, "index.current", true),

                ],
                'shops'      => $shops,


            ]
        )->table(function (InertiaTable $table) {
            $table->addSearchRows(
                [


                ]
            );
        });
    }

    public function show(Shop $shop): Response
    {



        session(['currentShop' => $shop->id]);


        $breadcrumbs = array_merge($this->breadcrumbs, [
            'shops' => [
                'route'           => 'shop.index',
                'routeParameters' => $shop->id,
                'name'            => $shop->code,
                'current'         => true
            ]
        ]);


        return Inertia::render(
            'Shops/Shop',
            [
                'headerData' => [
                    'module'      => $this->module,
                    'title'       => $shop->name,
                    'breadcrumbs' => $breadcrumbs,

                ],
                'shop'       => $shop
            ]

        );
    }
}
