<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 02 Feb 2022 02:53:05 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Hydrators;

use App\Models\Procurement\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class HydrateSupplier extends HydrateModel
{

    public string $commandSignature = 'hydrate:supplier {id} {--t|tenant=* : Tenant nickname}';


    public function handle(?Model $model): void
    {
        if (!$model) {
            return;
        }
        /** @var Supplier $supplier */
        $supplier = $model;

        $supplier->stats->update(
            [
                'number_purchase_orders' => $supplier->purchaseOrders()->count()

            ]
        );
    }

    protected function getModel(int $id): Supplier
    {
        return Supplier::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Supplier::withTrashed()->get();
    }


}

