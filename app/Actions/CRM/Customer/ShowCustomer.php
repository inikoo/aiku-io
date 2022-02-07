<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 28 Jan 2022 22:46:56 Malaysia Time, Kuala Lumpur, Malaysia
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
class ShowCustomer
{
    use AsAction;
    use WithInertia;


    public function handle()
    {
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("customers.{$this->customer->id}.view");
    }


    public function asInertia(Customer $customer, array $attributes = []): Response
    {
        $this->set('customer', $customer)->set('module', 'customer')->fill($attributes);

        $this->validateAttributes();


        session(['currentCustomer' => $customer->id]);


        return Inertia::render(
            'show-model',
            [
                'headerData' => [
                    'module'      => 'customers',
                    'title'       => $customer->name,
                    'breadcrumbs' => $this->get('breadcrumbs'),

                ],
                'model'       => $customer
            ]

        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {

        $this->fillFromRequest($request);

        $this->set('breadcrumbs', $this->breadcrumbs());
    }


    private function breadcrumbs(): array
    {
        /** @var Customer $customer */
        $customer = $this->get('customer');


        return array_merge(
            (new IndexCustomerInShop())->getBreadcrumbs(),
            [
                'customer' => [
                    'route'           => 'customers.show',
                    'routeParameters' => $customer->id,
                    'name'            => $customer->code,
                    'current'         => false
                ],
            ]
        );
    }


}
