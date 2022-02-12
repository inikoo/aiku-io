<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 03 Feb 2022 01:40:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Financials\Invoice;
use App\Models\CRM\Customer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class HydrateCustomer extends HydrateModel
{

    public string $commandSignature = 'hydrate:customer {id} {--t|tenant=* : Tenant nickname}';


    public function handle(Customer $customer): void
    {
        $this->contact($customer);
        $this->invoices($customer);
    }

    public function invoices(Customer $customer): void
    {
        $numberInvoices = $customer->invoices->count();
        $stats = [
            'number_invoices' => $numberInvoices,
        ];

        $customer->trade_state= match ($numberInvoices) {
            0 => 'none',
            1 => 'one',
            default => 'many'
        };
        $customer->save();

        $invoiceTypes = ['invoice', 'refund'];
        $invoiceTypeCounts = Invoice::where('customer_id', $customer->id)
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')->all();


        foreach ($invoiceTypes as $invoiceType) {
            $stats['number_invoices_type_'.$invoiceType] = Arr::get($invoiceTypeCounts, $invoiceType, 0);
        }


        $customer->stats->update($stats);
    }

    public function contact(Customer $customer): void
    {
        $customer->update(
            [

                'location' => $customer->billingAddress->getLocation()

            ]
        );
    }

    protected function getModel(int $id): Customer
    {
        return Customer::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Customer::withTrashed()->get();
    }

}


