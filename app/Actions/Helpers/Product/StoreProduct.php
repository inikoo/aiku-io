<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 29 Sep 2021 16:47:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Product;

use App\Models\Buying\Supplier;
use App\Models\Selling\Shop;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreProduct
{
    use AsAction;

    public function handle(Shop|Supplier $vendor, array $data): Model
    {
        return $vendor->products()->create($data);
    }
}
