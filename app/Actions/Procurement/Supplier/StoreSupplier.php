<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 11 Oct 2021 15:13:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Procurement\Supplier;

use App\Actions\Helpers\Address\StoreAddress;
use App\Models\Utils\ActionResult;
use App\Models\Account\Tenant;
use App\Models\Aiku\Aiku;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreSupplier
{
    use AsAction;
    use WithAttributes;

    public function handle(Tenant|Aiku|Agent $parent, array $data, array $addressData): ActionResult
    {
        $res = new ActionResult();

        /** @var Supplier $supplier */
        $supplier = $parent->suppliers()->create($data);
        $supplier->stats()->create();


        $addresses               = [];
        $address                 = StoreAddress::run($addressData);
        $addresses[$address->id] = ['scope' => 'contact'];
        $supplier->addresses()->sync($addresses);


        $supplier->address_id = $address->id;
        $supplier->location   = $supplier->getLocation();
        $supplier->save();

        $res->model    = $supplier;
        $res->model_id = $supplier->id;
        $res->status   = $res->model_id ? 'inserted' : 'error';

        return $res;
    }

    public function rules(): array
    {
        return [
            'name'                     => 'required|string',
            'code'                     => 'required|string',
            'email'                    => 'email',
            'phone'                    => 'phone:AUTO',



        ];
    }

    public function asInertia(Request $request,$parent): RedirectResponse
    {
        $this->fillFromRequest($request);
        $this->validateAttributes();

        $modelData = $request->only(
            'code',
            'name',
            'company_name',
            'contact_name',
            'email',
            'phone',
            'address',

        );


        dd($modelData);

        $res = $this->handle(
            $parent,
            $modelData

        );


        return Redirect::route('human_resources.employees.show', $res->model_id);
    }
}


