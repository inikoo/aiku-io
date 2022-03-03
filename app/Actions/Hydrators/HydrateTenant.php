<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 10 Feb 2022 01:37:24 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;


use App\Models\Account\Tenant;
use App\Models\Auth\User;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Guest;
use App\Models\Marketing\Shop;
use App\Models\Marketing\ShopStats;
use App\Models\Production\Workshop;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;


class HydrateTenant
{
    use AsAction;

    public string $commandSignature = 'hydrate:tenant {code?}';


    public function handle(): void
    {
        $this->customerStats();
        $this->employeeStats();
        $this->userStats();
        $this->shopStats();
        $this->productionStats();
        //$this->orderStats($tenant);
        //$this->productStats($tenant);
        //$this->invoices($tenant);
    }

    public function productionStats(){

        $numberWorkshops       = Workshop::count();

        App('currentTenant')->procurementStats->update(
            [
                'number_workshops'=>$numberWorkshops
            ]
        );
        App('currentTenant')->stats->update(
            [
                'has_production'   => App('currentTenant')->procurementStats->number_workshops > 0,
            ]
        );

    }

    public function shopStats()
    {
        $stats = [
            'number_shops' => Shop::count()
        ];

        $shopTypes     = ['shop', 'fulfilment_house'];
        $shopTypeCount = Shop::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')->all();

        foreach ($shopTypes as $shopType) {
            $stats['number_shops_type_'.$shopType] = Arr::get($shopTypeCount, $shopType, 0);
        }

        $userSubtypes     = ['b2b', 'b2c', 'storage', 'fulfilment', 'dropshipping'];
        $userSubtypeCount = Shop::selectRaw('subtype, count(*) as total')
            ->groupBy('subtype')
            ->pluck('total', 'subtype')->all();

        foreach ($userSubtypes as $userSubtype) {
            $stats['number_shops_subtype_'.$userSubtype] = Arr::get($userSubtypeCount, $userSubtype, 0);
        }

        App('currentTenant')->marketingStats->update($stats);

        App('currentTenant')->stats->update(
            [
                'has_fulfilment'   => App('currentTenant')->marketingStats->number_shops_subtype_fulfilment > 0,
                'has_dropshipping' => App('currentTenant')->marketingStats->number_shops_subtype_dropshipping > 0
            ]
        );
    }

    public function customerStats()
    {
        App('currentTenant')->marketingStats->update(
            [
                'number_customers' => ShopStats::sum('number_customers')
            ]
        );
    }

    public function employeeStats()
    {
        $stats = [
            'number_employees' => Employee::count()
        ];

        $employeeStates     = ['hired', 'working', 'left'];
        $employeeStateCount = Employee::selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();


        foreach ($employeeStates as $employeeState) {
            $stats['number_employees_state_'.$employeeState] = Arr::get($employeeStateCount, $employeeState, 0);
        }
        App('currentTenant')->stats->update($stats);
    }

    public function userStats()
    {
        $numberUsers       = (new User())->count();
        $numberActiveUsers = (new User())->where('status', true)->count();

        $numberGuests       = Guest::count();
        $numberActiveGuests = Guest::where('status', true)->count();


        $stats = [

            'number_guests'                 => Guest::count(),
            'number_guests_status_active'   => $numberActiveGuests,
            'number_guests_status_inactive' => $numberGuests - $numberActiveGuests,
            'number_users'                  => $numberUsers,
            'number_users_status_active'    => $numberActiveUsers,
            'number_users_status_inactive'  => $numberUsers - $numberActiveUsers

        ];


        $userTypes     = ['tenant', 'employee', 'guest', 'supplier', 'agent', 'customer'];
        $userTypeCount = User::selectRaw('LOWER(userable_type) as userable_type, count(*) as total')
            ->groupBy('userable_type')
            ->pluck('total', 'userable_type')->all();


        foreach ($userTypes as $userType) {
            $stats['number_users_type_'.$userType] = Arr::get($userTypeCount, $userType, 0);
        }
        App('currentTenant')->stats->update($stats);
    }

    /*


    public function customerNumberInvoicesStats(Tenant $tenant)
    {
        $stats = [];

        $customerNumberInvoicesStates = ['none', 'one', 'many'];

        $numberInvoicesStateCounts = Customer::where('tenant_id', $tenant->id)
            ->selectRaw('trade_state, count(*) as total')
            ->groupBy('trade_state')
            ->pluck('total', 'trade_state')->all();


        foreach ($customerNumberInvoicesStates as $customerNumberInvoicesState) {
            $stats['number_customers_number_invoices_'.$customerNumberInvoicesState] =
                Arr::get($numberInvoicesStateCounts, $customerNumberInvoicesState, 0);
        }
        $tenant->stats->update($stats);
    }

    public function orderStats(Tenant $tenant)
    {
        $stats       = [
            'number_orders' => $tenant->orders->count(),
        ];
        $orderStates = ['in-basket', 'in-process', 'in-warehouse', 'packed', 'packed-done', 'dispatched', 'returned', 'cancelled'];
        $stateCounts = Order::where('tenant_id', $tenant->id)
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();


        foreach ($orderStates as $orderState) {
            $stats['number_orders_'.str_replace('-', '_', $orderState)] = Arr::get($stateCounts, $orderState, 0);
        }
        $tenant->stats->update($stats);
    }

    public function productStats(Tenant $tenant)
    {
        $productStates = ['creating', 'active', 'no-available', 'discontinuing', 'discontinued'];
        $stateCounts   = Product::where('tenant_id', $tenant->id)
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();
        $stats         = [
            'number_products' => $tenant->products->count(),
        ];
        foreach ($productStates as $productState) {
            $stats['number_products_'.str_replace('-', '_', $productState)] = Arr::get($stateCounts, $productState, 0);
        }
        $tenant->stats->update($stats);
    }

    public function invoices(Tenant $tenant): void
    {
        $stats             = [
            'number_invoices' => $tenant->invoices->count(),

        ];
        $invoiceTypes      = ['invoice', 'refund'];
        $invoiceTypeCounts = Invoice::where('tenant_id', $tenant->id)
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')->all();


        foreach ($invoiceTypes as $invoiceType) {
            $stats['number_invoices_'.$invoiceType] = Arr::get($invoiceTypeCounts, $invoiceType, 0);
        }

        $tenant->stats->update($stats);
    }

    public function sales(Tenant $tenant){




    }
    */


    public function asCommand(Command $command): void
    {

        if ($command->argument('code')) {
            $tenants = (new Tenant())->where('code', $command->argument('code'))->get();
        } else {
            $tenants = Tenant::all();
        }

        $tenants->eachCurrent(function () {
            $this->handle();
        });
    }


}


