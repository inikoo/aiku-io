<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 15 Apr 2022 00:23:44 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Marketing\Product;


use App\Actions\Marketing\Shop\ShowShop;
use App\Actions\Marketing\UseCatalogue;
use App\Models\Marketing\Shop;
use Lorisleiva\Actions\ActionRequest;

use function __;


/**
 * @property Shop $shop
 */
class IndexProductInShop extends IndexProduct
{

    use UseCatalogue;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.products.view.{$this->shop->id}");
    }

    public function queryConditions($query)
    {
        return $query->where('shop_id', $this->shop->id)->select($this->select);
    }

    public function asInertia(Shop $shop)
    {
        $this->set('shop', $shop);
        session(['currentShop' => $shop->id]);
        $this->validateAttributes();

        return $this->getInertia();
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title'       => __('Products'),
                'breadcrumbs' => $this->getBreadcrumbs($this->shop),
                'sectionRoot' => 'marketing.shops.show.catalogue',
                'metaSection' => 'shop',
                'topTabs'     => $this->getCatalogueTabs('products')
            ]
        );
        $this->fillFromRequest($request);
    }

    public function getBreadcrumbs(Shop $shop): array
    {
        return array_merge(
            (new ShowShop())->getBreadcrumbs($shop),
            [
                'marketing.shops.show.products.index' => [
                    'route'           => 'marketing.shops.show.products.index',
                    'routeParameters' => $shop->id,
                    'modelLabel'      => [
                        'label' => __('products')
                    ],

                ],
            ]
        );
    }


}
