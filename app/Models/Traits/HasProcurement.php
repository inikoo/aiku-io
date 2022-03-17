<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 17 Mar 2022 21:46:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Models\Traits;


use App\Models\Procurement\ProcurementDelivery;
use App\Models\Procurement\PurchaseOrder;
use App\Models\Procurement\SupplierProduct;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasProcurement
{

    public function supplierProducts(): HasMany
    {
        return $this->hasMany(SupplierProduct::class);
    }

    public function purchaseOrders(): MorphMany
    {
        return $this->morphMany(PurchaseOrder::class, 'vendor');
    }

    public function procurementDeliveries(): MorphMany
    {
        return $this->morphMany(ProcurementDelivery::class, 'vendor');
    }

}


