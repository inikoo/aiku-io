<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 13 Feb 2022 03:34:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\CRM\Customer;

use App\Actions\UI\WithInertia;
use App\Models\CRM\Customer;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


/**
 * @property Customer $customer
 */
class ShowCustomerInShop
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.customers.view.{$this->customer->shop_id}");
    }


    public function asInertia(Customer $customer, array $attributes = []): Response
    {
        $this->set('customer', $customer)->fill($attributes);

        $this->validateAttributes();


        return Inertia::render(
            'show-model',
            [
                'breadcrumbs' => $this->getBreadcrumbs($customer),
                'headerData'  => [
                    'title' => $customer->name,
                ],
                'model'       => $customer,
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

    }


    public function getBreadcrumbs(Customer $customer): array
    {
        return array_merge(
            (new IndexCustomerInShop())->getBreadcrumbs($customer->shop),
            [
                'customer' => [
                    'route' => 'marketing.shops.show.customers.show',
                    'routeParameters' => [$customer->shop_id, $customer->id],
                    'name' => $customer->getFormattedID(),
                ],
            ]
        );
    }


}
