<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Jan 2022 22:48:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Actions\Marketing\ShowMarketingDashboard;
use App\Models\Marketing\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


use App\Actions\UI\WithInertia;

use function __;

/**
 * @property \Illuminate\Support\Collection $allowed_shops
 * @property bool $canViewAll
 */
class IndexCustomerInTenant extends IndexCustomer
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        $canView = $request->user()->hasPermissionTo("shops.customers.view");
        $this->canViewAll=$canView;
        if (!$canView) {
            $this->allowed_shops = Shop::withTrashed()->get()->pluck('id')->filter(function ($shopID) use ($request) {
                $request->user()->can("shops.customers.$shopID.view");
            });
            $canView = $this->allowed_shops->count()>0;
        }

        return $canView;
    }
    public function queryConditions($query){
        $select=array_merge(array_diff( $this->select, ['id','name'] ), ['customers.id as id', 'shops.code as shop_code','customers.name as name']);

        if(!$this->canViewAll){
            $query->whereIn('shop_id',$this->allowed_shops->all()) ;
        }
        $query->select($select)->leftJoin('shops','customers.shop_id','=','shops.id');

        return $query;
    }

    public function asInertia()
    {
        $this->set('canViewAll',false);
        $this->set('allowed_shops',[]);
        $this->validateAttributes();

        return $this->getInertia();
    }


    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Customers'),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'sectionRoot' => 'marketing.dashboard',
                'module' => 'marketing',
                'metaSection' => 'shops'

            ]
        );
        $this->fillFromRequest($request);
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowMarketingDashboard())->getBreadcrumbs(),
            [
                'marketing.customers.index' => [
                    'route' => 'marketing.customers.index',
                    'modelLabel' => [
                        'label' => __('customers')
                    ],
                ],
            ]
        );
    }




}
