<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 15:17:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;


use App\Models\CRM\Customer;
use App\Models\Trade\Shop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

use App\Actions\UI\WithInertia;

use function __;


/**
 * @property string $title
 */
class IndexCustomerInShop extends IndexCustomer
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.customers.view") || $request->user()->hasPermissionTo("shops.customers.{$this->parentParameters->id}.view");
    }


    public function handle(): LengthAwarePaginator
    {
        return QueryBuilder::for(Customer::class)
            ->select($this->select)
            ->where('shop_id', $this->parentParameters->id)
            ->defaultSorts('-id')
            ->allowedSorts(['name', 'id','location'])
            ->paginate()
            ->withQueryString();
    }


    public function asInertia(Shop $shop)
    {
        $this->set('parentParameters', $shop)->set('parent','shop');
        $this->validateAttributes();

        return $this->getInertia();



    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $request->merge(
            [
                'title' => __('Customers in :shop', ['shop' => $this->parentParameters->code])


            ]
        );
        $this->fillFromRequest($request);

    }







}
