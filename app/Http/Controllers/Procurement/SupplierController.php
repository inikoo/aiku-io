<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 03:39:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Http\Controllers\Procurement;

use App\Actions\Procurement\Supplier\IndexSupplier;
use App\Http\Controllers\Controller;
use App\Models\Procurement\Agent;
use App\Models\Procurement\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;


class SupplierController extends Controller
{


    public function index(): Response
    {
        return IndexSupplier::make()->asInertia(parent: App('currentTenant'));
    }

    public function indexInAgent(Agent $agent): Response
    {
        return IndexSupplier::make()->asInertia(parent: $agent);
    }

    public function show(Supplier $supplier): Response
    {
        return ShowSupplier::make()->asInertia($supplier);
    }

    public function edit(Supplier $supplier): Response
    {
        return ShowEditSupplier::make()->asInertia(supplier: $supplier);
    }

    public function update(Supplier $supplier, Request $request): RedirectResponse
    {
        return UpdateSupplier::make()->asInertia(supplier: $supplier, request: $request);
    }

}
